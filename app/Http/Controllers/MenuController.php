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

        $offset = ($page - 1) * $this->per_page;

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
        $total_page = ceil($total_result / $this->per_page);

        $menus = Menu::where($wheres)
            ->limit($this->per_page)
            ->offset($offset)
            ->get();

        $items = array();
        foreach ($menus as $menu) {
            array_push($items, $menu);
        }

        return $this->_output_success('菜单列表', [
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
    public function side_menu(Request $request)
    {
        $wheres = array(
            ['parent_id', '=', 0],
            ['status', '=', 1],
            ['type', '=', 1]
        );

        $output_array = array();
        $top_menus = Menu::where($wheres)->get();
        if ($top_menus) {
            foreach ($top_menus as $top_menu) {
                $sub_menus = Menu::where(array(
                    ['parent_id', '=', $top_menu->id],
                    ['status', '=', 1],
                    ['type', '=', 1]
                ))->get();

                // 过滤没有子菜单的选项
                if (! $sub_menus) { continue; }

                $tmp_menu = array(
                    'icon' => $top_menu->icon,
                    'title' => $top_menu->title,
                    'uri' => $top_menu->uri,
                    'sub_menus' => array()
                );
                foreach ($sub_menus as $sub_menu) {
                    array_push($tmp_menu['sub_menus'], array(
                        'icon' => $sub_menu->icon,
                        'title' => $sub_menu->title,
                        'uri' => $sub_menu->uri
                    ));
                }

                array_push($output_array, $tmp_menu);
                unset($tmp_menu);
            }
        }

        return $this->_output_success('侧边菜单', $output_array);
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
