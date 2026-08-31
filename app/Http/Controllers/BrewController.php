<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Reading;
use App\Models\Recipe;
use App\Models\Reminder;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class BrewController extends Controller
{
    public function index() {
        return view('brew', [
            'recipes' => Recipe::withCount('batches')->latest()->get(),
            'batches' => Batch::with(['recipe', 'readings', 'logs'])->latest('brewed_at')->get(),
            'active' => Batch::with('recipe')->whereIn('status', ['brewing','fermenting','conditioning'])->latest('brewed_at')->get(),
            'reminders' => Reminder::with('batch.recipe')->orderBy('completed')->orderBy('due_at')->get(),
            'inventory' => InventoryItem::orderBy('category')->orderBy('name')->get(),
        ]);
    }

    public function storeRecipe(Request $request) {
        Recipe::create($this->validatedRecipe($request));
        return back()->with('success', 'Receta guardada.');
    }

    public function updateRecipe(Request $request, Recipe $recipe) {
        $recipe->update($this->validatedRecipe($request));
        return back()->with('success', 'Receta actualizada.');
    }

    public function destroyRecipe(Recipe $recipe) {
        $recipe->delete();
        return back()->with('success', 'Receta y su historial eliminados.');
    }

    private function validatedRecipe(Request $request): array {
        return $request->validate([
            'name'=>'required|max:120', 'style'=>'nullable|max:100', 'batch_size'=>'required|numeric|min:1',
            'og'=>'nullable|numeric|between:1,1.2', 'fg'=>'nullable|numeric|between:0.99,1.2', 'abv'=>'nullable|numeric|min:0|max:30',
            'ibu'=>'nullable|integer|min:0|max:200', 'color'=>'nullable|max:20', 'ingredients'=>'nullable', 'process'=>'nullable', 'notes'=>'nullable',
            'efficiency'=>'nullable|numeric|between:0,100', 'mash_ph'=>'nullable|numeric|between:4,7', 'water_profile'=>'nullable',
            'mash_schedule'=>'nullable', 'boil_plan'=>'nullable', 'fermentation_plan'=>'nullable', 'clarification_plan'=>'nullable', 'packaging_plan'=>'nullable'
        ]);
    }

    public function storeBatch(Request $request) {
        $data = $request->validate(['recipe_id'=>'required|exists:recipes,id','code'=>'required|max:40|unique:batches','brewed_at'=>'required|date',
            'status'=>'required|in:planned,brewing,fermenting,conditioning,packaged,finished','volume'=>'nullable|numeric|min:0',
            'og'=>'nullable|numeric|between:1,1.2','fg'=>'nullable|numeric|between:0.99,1.2','temperature'=>'nullable|numeric','notes'=>'nullable',
            'pre_boil_volume'=>'nullable|numeric|min:0','pre_boil_gravity'=>'nullable|numeric|between:0.99,1.2','post_boil_volume'=>'nullable|numeric|min:0',
            'pitch_temperature'=>'nullable|numeric','yeast'=>'nullable|max:255']);
        Batch::create($data); return back()->with('success', 'Cocción registrada.');
    }

    public function updateBatch(Request $request, Batch $batch) {
        $batch->update($request->validate(['status'=>'required|in:planned,brewing,fermenting,conditioning,packaged,finished','fg'=>'nullable|numeric|between:0.99,1.2','temperature'=>'nullable|numeric','rating'=>'nullable|integer|between:1,5','notes'=>'nullable']));
        return back()->with('success', 'Lote actualizado.');
    }

    public function storeReading(Request $request, Batch $batch) {
        $data = $request->validate(['measured_at'=>'required|date','gravity'=>'nullable|numeric|between:0.99,1.2','temperature'=>'nullable|numeric','ph'=>'nullable|numeric|between:2,8','notes'=>'nullable']);
        $batch->readings()->create($data); return back()->with('success', 'Medición anotada.');
    }

    public function storeLog(Request $request, Batch $batch) {
        $data = $request->validate([
            'stage'=>'required|in:water,milling,mash,sparge,boil,chill,fermentation,conditioning,clarification,packaging,tasting',
            'occurred_at'=>'required|date', 'title'=>'required|max:150', 'value'=>'nullable|numeric', 'unit'=>'nullable|max:20',
            'temperature'=>'nullable|numeric|between:-5,120', 'gravity'=>'nullable|numeric|between:0.99,1.2',
            'ph'=>'nullable|numeric|between:2,14', 'duration'=>'nullable|integer|min:0|max:10000', 'notes'=>'nullable'
        ]);
        $batch->logs()->create($data);
        return back()->with('success', 'Evento del proceso registrado.');
    }

    public function storeReminder(Request $request) {
        Reminder::create($request->validate(['batch_id'=>'nullable|exists:batches,id','title'=>'required|max:150','due_at'=>'required|date','notes'=>'nullable']));
        return back()->with('success','Recordatorio programado.');
    }

    public function toggleReminder(Reminder $reminder) {
        $reminder->update(['completed'=>!$reminder->completed]);
        return back()->with('success',$reminder->completed?'Tarea completada.':'Tarea reabierta.');
    }

    public function storeInventory(Request $request) {
        InventoryItem::create($request->validate(['name'=>'required|max:150','category'=>'required|in:malt,hop,yeast,water,adjunct,clarifier,packaging,other','quantity'=>'required|numeric|min:0','unit'=>'required|max:20','lot'=>'nullable|max:100','expires_at'=>'nullable|date','minimum_stock'=>'nullable|numeric|min:0','notes'=>'nullable']));
        return back()->with('success','Insumo agregado al inventario.');
    }

    public function adjustInventory(Request $request, InventoryItem $item) {
        $data=$request->validate(['amount'=>'required|numeric','operation'=>'required|in:add,use']);
        $amount=(float)$data['amount']*($data['operation']==='use'?-1:1);
        $item->update(['quantity'=>max(0,(float)$item->quantity+$amount)]);
        return back()->with('success','Stock actualizado.');
    }
}
