<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request)
    {
        $code = $request->get('code');
        $title = $request->get('title');
        $level = $request->get('level');
        $page = $request->get('page', 1);

        $offset = ($page - 1) * $this->per_page;

        $wheres = [];
        if ($code) {
            array_push($wheres, ['code', '=', $code]);
        }

        if ($title) {
            array_push($wheres, ['title', 'like', "%{$title}%"]);
        }

        if ($level) {
            array_push($wheres, ['level', '=', $level]);
        }

        $total_result = Task::where($wheres)->count();
        $total_page = ceil($total_result / $this->per_page);

        $tasks = Task::where($wheres)
            ->limit($this->per_page)
            ->offset($offset)
            ->get();

        $items = array();
        foreach ($tasks as $task) {
            array_push($items, $task);
        }

        return $this->_output_success('项目列表', [
            'total_result' => $total_result,
            'total_page' => $total_page,
            'per_page' => $this->per_page,
            'page' => $page,
            'items' => $items
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'parent_id' => 'integer',
                'project_code' => 'string',
                'level' => 'required|integer',
                'date_completed' => 'required'
            ]);
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $name = $request->post('name');
        $parent_id = $request->post('parent_id', 0);
        $project_code = $request->post('project_code', '');
        $level = $request->post('level', 3);
        $date_completed = $request->post('date_completed');

        $task = new Task();
        $task->code = generate_code();
        $task->name = $name;
        $task->parent_id = $parent_id;
        $task->project_code = $project_code;
        $task->level = $level;
        $task->date_completed = $date_completed;
        $result = $task->save();

        if (! $result) {
            return $this->_output_error('添加失败');
        }

        return $this->_output_success('添加成功');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function mod(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|integer',
                'name' => 'required|string',
                'parent_id' => 'integer',
                'project_code' => 'string',
                'level' => 'required|integer',
                'date_completed' => 'required'
            ]);
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $name = $request->post('name');
        $parent_id = $request->post('parent_id', 0);
        $project_code = $request->post('project_code', '');
        $level = $request->post('level', 3);
        $date_completed = $request->post('date_completed');

        $task = Task::find($id);
        $task->name = $name;
        $task->parent_id = $parent_id;
        $task->project_code = $project_code;
        $task->level = $level;
        $task->date_completed = $date_completed;
        $result = $task->save();

        if (! $result) {
            return $this->_output_error('编辑失败');
        }

        return $this->_output_success('编辑成功');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function del(Request $request)
    {
        $id = $request->get('id');

        $result = Task::destroy($id);
        if (! $result) {
            return $this->_output_error('删除失败');
        }

        return $this->_output_success('删除成功');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function set_status(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|integer',
                'status' => 'required|integer',
            ]);
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $status = $request->post('status');

        $task = Task::find($id);
        if (! $task) {
            return $this->_output_error('找不到项目');
        }

        $task->status = $status;
        if ($status == TaskStatus::COMPLETED) {
            $task->date_completed = date('Y-m-d H:i:s');
        }
        $result = $task->save();

        if (! $result) {
            return $this->_output_error('设置失败');
        }

        return $this->_output_success('设置成功');
    }
}
