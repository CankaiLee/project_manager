<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
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

        $total_result = Project::where($wheres)->count();
        $total_page = ceil($total_result / $this->per_page);

        $projects = Project::where($wheres)
            ->limit($this->per_page)
            ->offset($offset)
            ->get();

        $items = array();
        foreach ($projects as $project) {
            array_push($items, $project);
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
                'title' => 'required|string',
                'sub_title' => 'string',
                'intro' => 'required|string',
                'level' => 'required|integer',
                'logo_upload_id' => 'required|integer',
                'date_completed' => 'required'
            ]);
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $title = $request->post('title');
        $sub_title = $request->post('sub_title', '');
        $intro = $request->post('intro', '');
        $level = $request->post('level', 3);
        $logo_upload_id = $request->post('logo_upload_id', 0);
        $date_completed = $request->post('date_completed');

        $project = new Project();
        $project->code = generate_code();
        $project->title = $title;
        $project->sub_title = $sub_title;
        $project->intro = $intro;
        $project->level = $level;
        $project->logo_upload_id = $logo_upload_id;
        $project->date_completed = $date_completed;
        $result = $project->save();

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
                'title' => 'required|string',
                'sub_title' => 'string',
                'intro' => 'required|string',
                'level' => 'required|integer',
                'logo_upload_id' => 'required|integer',
                'date_completed' => 'required'
            ]);
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $title = $request->post('title');
        $sub_title = $request->post('sub_title', '');
        $intro = $request->post('intro', '');
        $level = $request->post('level', 3);
        $logo_upload_id = $request->post('logo_upload_id', 0);
        $date_completed = $request->post('date_completed');

        $project = Project::find($id);
        $project->title = $title;
        $project->sub_title = $sub_title;
        $project->intro = $intro;
        $project->level = $level;
        $project->logo_upload_id = $logo_upload_id;
        $project->date_completed = $date_completed;
        $result = $project->save();

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

        $project = Project::find($id);
        if (in_array($project->status, [ProjectStatus::PROCESSING])) {
            return $this->_output_error('项目正在进行中，请先取消之后才能删除');
        }

        $task_wheres = array(
            ['project_code', '=', $project->code],
            ['status', '=', TaskStatus::NOT_YET]
        );
        $tasks = Task::where($task_wheres)->get();
        if ($tasks) {
            return $this->_output_error('项目还有任务未完成');
        }

        $result = Project::destroy($id);
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

        $project = Project::find($id);
        if (! $project) {
            return $this->_output_error('找不到项目');
        }

        $project->status = $status;
        if ($status == ProjectStatus::COMPLETED) {
            $project->date_completed = date('Y-m-d H:i:s');
        }
        $result = $project->save();

        if (! $result) {
            return $this->_output_error('设置失败');
        }

        return $this->_output_success('设置成功');
    }
}
