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
        $parent_id = $request->get('parent_id', '');
        $status = $request->get('status', '');
        $title = $request->get('title');
        $page = $request->get('page', 1);

        $offset = ($page - 1) * $this->per_page;

        $wheres = array();

        if ($status != '') {
            array_push($wheres, array(
                'status', '=', $status
            ));
        }

        if ($parent_id != '') {
            array_push($wheres, array(
                'parent_id', '=', "$parent_id"
            ));
        }

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
            $parent_title = '顶级菜单';
            if ($menu->parent_id > 0) {
                $parent_menu = Menu::find($menu->parent_id);
                $parent_title = $parent_menu->title;
            }
            array_push($items, array(
                'key' => $menu->id,
                'id' => $menu->id,
                'parent_title' => $parent_title,
                'title' => $menu->title,
                'path' => $menu->uri,
                'type' => $menu->type,
                'status' => $menu->status,
                'created_at' => $menu->created_at
            ));
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
    public function get(Request $request)
    {
        try {
            $id = $request->get('id');

            $menu = Menu::find($id);
            if (! $menu) {
                return $this->_output_error('找不到这个菜单');
            }

            return $this->_output_success('菜单详情', $menu);
        } catch (\Exception $e) {
            return $this->_output_exception($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function parent_menu(Request $request)
    {
        try {
            $wheres = array(
                ['parent_id', '=', 0],
                ['status', '=', 1]
            );

            $menus = Menu::where($wheres)->get();

            $output_array = array(
                ['id' => 0, 'title' => '顶级菜单']
            );
            foreach ($menus as $menu) {
                array_push($output_array, array(
                    'id' => $menu->id,
                    'title' => $menu->title
                ));
            }

            return $this->_output_success('父级菜单', $output_array);
        } catch (\Exception $e) {
            return $this->_output_exception($e);
        }
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
                'uri' => 'required|string',
            ));
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $parent_id = $request->post('parent_id');
        $title = $request->post('title');
        $type = $request->post('type');
        $uri = $request->post('uri');
        $icon = $request->post('icon');
        $status = $request->post('status');

        $menu = new Menu();
        $menu->parent_id = $parent_id;
        $menu->title = $title;
        $menu->type = $type;
        $menu->uri = $uri;
        $menu->status = $status;
        $menu->icon = $icon;
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
                'uri' => 'required|string',
            ));
        } catch (ValidationException $e) {
            return $this->_output_exception($e);
        }

        $id = $request->post('id');
        $parent_id = $request->post('parent_id');
        $title = $request->post('title');
        $type = $request->post('type');
        $uri = $request->post('uri');
        $status = $request->post('status');
        $icon = $request->post('icon');

        $menu = Menu::find($id);
        $menu->parent_id = $parent_id;
        $menu->title = $title;
        $menu->type = $type;
        $menu->uri = $uri;
        $menu->icon = $icon;
        $menu->status = $status;

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
