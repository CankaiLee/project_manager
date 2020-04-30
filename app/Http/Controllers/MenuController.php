<?php
namespace App\Http\Controllers;


use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MenuController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function all(Request $request)
    {
        $parent_id = $request->get('parent_id', 0);
        $title = $request->get('title');
        $page = $request->get('page', 1);

        $per_page = 10;

        $offset = ($page - 1) * $per_page;

        $wheres = array(
            ['parent_id', '=', $parent_id],
            ['status', '=', 1],
        );

        if ($title) {
            array_push($wheres, array(
                'title', 'like', "%{$title}%"
            ));
        }


        $total_result = Menu::where($wheres)->count();
        $total_page = ceil($total_result / $per_page);

        $menus = Menu::where($wheres)
            ->limit($per_page)
            ->offset($offset)
            ->get();

        $items = array();
        foreach ($menus as $menu) {
            array_push($items, $menu);
        }

        return $this->_output_success('菜单列表', [
            'total_result' => $total_result,
            'total_page' => $total_page,
            'per_page' => $per_page,
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
            $this->validate($request, array(
                'parent_id' => 'required|integer',
                'title' => 'required|string',
                'type' => 'required|integer',
                'uri' => 'required|string'
            ));
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $parent_id = $request->post('parent_id');
        $title = $request->post('title');
        $type = $request->post('type');
        $uri = $request->post('uri');

        $menu = new Menu();
        $menu->parent_id = $parent_id;
        $menu->title = $title;
        $menu->type = $type;
        $menu->uri = $uri;
        $result = $menu->save();

        if (! $result) {
            return $this->_output_error('添加菜单失败');
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
            $this->validate($request, array(
                'id' => 'required|integer',
                'parent_id' => 'required|integer',
                'title' => 'required|string',
                'type' => 'required|integer',
                'uri' => 'required|string'
            ));
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $parent_id = $request->post('parent_id');
        $title = $request->post('title');
        $type = $request->post('type');
        $uri = $request->post('uri');

        $menu = Menu::find($id);
        $menu->parent_id = $parent_id;
        $menu->title = $title;
        $menu->type = $type;
        $menu->uri = $uri;
        $result = $menu->save();

        if (! $result) {
            return $this->_output_error('修改菜单失败');
        }

        return $this->_output_success('修改成功');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function del(Request $request)
    {
        $id = $request->get('id');
        $menu = Menu::find($id);
        if ($menu->status) {
            return $this->_output_error('菜单正在启用，删除失败');
        }

        $result = Menu::destroy($id);

        if (! $result) {
            return $this->_output_error('删除菜单失败');
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
            $this->validate($request, array(
                'id' => 'required|integer',
                'status' => 'required|integer'
            ));
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $status = $request->post('status');

        $menu = Menu::find($id);
        $menu->status = $status;
        $result = $menu->save();

        if (! $result) {
            return $this->_output_error('设置菜单失败');
        }

        return $this->_output_success('设置成功');
    }
}
