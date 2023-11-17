<?php

declare(strict_types=1);

namespace App\Modules\Defaults\Auth\Model;
use Core\Facades\DB;
use Phalcon\Mvc\Model;
use Core\Models\Behavior\SoftDelete;
use PDO;
use Phalcon\Mvc\Model\Behavior\Timestampable;

class MenuModel extends Model
{
    public function initialize()
    {
        $this->setSource('menu');

        $this->hasMany('id_menu', MenuModel::class, 'parent_menu', [
            'alias' => 'children', 'reusable' => true
        ]);
    }

    public static function getUserMenuList()
    {
        $di      = \Phalcon\Di::getDefault();
        $session = $di->getShared('session');
        $db      = $di->getShared('db');
        $pdam_id = $session->user['pdam_id'];

        $sql = "
            SELECT * 
            FROM menu 
            WHERE 1=1 
                AND is_aktif=1 
                AND is_tampil=1 
                AND id_menu in (SELECT id_menu FROM menu_otorisasi where id_role=? AND pdam_id = '".$pdam_id."') ORDER by urutan;
        ";

        // $result = DB::query($sql)->fetchAll(PDO::FETCH_OBJ);
        $result = DB::query($sql, [$session->user['id_role']])->fetchAll(PDO::FETCH_OBJ);

        return self::toTree($result);
    }

    private static function toTree($flatMenu, $idParent = 0)
    {
        $menus = [];
        foreach($flatMenu as $key => $item)
        {
            if($item->parent_menu == $idParent)
            {
                unset($flatMenu[$key]);
                $children = self::toTree($flatMenu, $item->id_menu);
                $item->has_children = !empty($children);
                $item->children = (array) $children;
                $menus[] = $item;
            }
        }

        return $menus;
    }

    public static function injectUserMenuList($viewVar)
    {
        $di   = \Phalcon\Di::getDefault();
        $view = $di->getShared('view');

        $menus = static::getUserMenuList();

        $view->setVar($viewVar, $menus);
    }

    /*
    public static function getCountSuratMasuk()
    {
        $di      = \Phalcon\Di::getDefault();
        $db      = $di->getShared('db');

        $sql = "
            SELECT count(id) as jumlah FROM vw_surat_masuk WHERE status_surat = 0 
        ";
        $result = $db->query($sql);
        $data = $result->fetchAll(\Pdo::FETCH_OBJ);
        $countSuratMasuk = $data[0]->jumlah;
        
        return $countSuratMasuk;
    }
    */
}