<?php

use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Task;


Route::get('/', function () {
    return redirect()->route('tasks.index');
});
Route::get('/tasks', function () {
    return view('index', [
        'tasks' => Task::latest()->where('completed', true)->get()
    ]);
})->name('tasks.index');

Route::get('/tasks/{task}/edit', function (Task $task) {

    return view('edit', ['task' =>  $task]);
})->name('tasks.update');


Route::get('/tasks/{task}', function (Task $task) {

    return view('show', ['task' =>  $task]);
})->name('tasks.show');

Route::view('/task/create', 'create')->name('task.create');


Route::fallback(function () {
    return "still got somewhere!";
});

Route::post('/tasks', function (Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required'
    ]);

    $task = new Task;
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    $task->save();

    return redirect()->route('tasks.show', [
        'id' => $task->id
    ])->with('success', 'Task created successfully!');
})->name('tasks.store');

Route::put('/tasks/{task}', function (Task $task, Request $request) {
    $data = $request->validate([
        'title' => 'required|max:255',
        'description' => 'required',
        'long_description' => 'required'
    ]);

    $task = Task::findOrFail($task);
    $task->title = $data['title'];
    $task->description = $data['description'];
    $task->long_description = $data['long_description'];

    $task->save();

    return redirect()->route('tasks.show', [
        'id' => $task->id
    ])->with('success', 'Task updated successfully!');
})->name('tasks.update');




Route::get('/test-database', function () {
    try {
        DB::connection()->getPdo();
        return 'Database connection is successful';
    } catch (\Exception $e) {
        return 'Database connection failed ' . $e->getMessage();
    }
});



// Route::get('/xxx', function () {
//     return 'Hello';
// })->name('hello');

// Route::get('hallo', function () {
//     return redirect()->route('hello');
// });

// Route::get('/greet/{name}', function ($name) {
//     return 'Hello ' . $name . '!';
// });
