<?php

namespace Clavel\Basic\Services;

use App\Models\Idioma;
use Nwidart\Menus\Facades\Menu;
use Clavel\Basic\Models\MenuItem;
use Illuminate\Support\Facades\App;

class CustomMenu
{
    protected $menuGenerated = false;

    protected $nodos_precesados = [];
    /**
     * Render the menu tag by given name.
     *
     * @param $name
     * @param null $presenter
     *
     * @return string
     */
    public function render($menuName)
    {
        $this->nodos_precesados = [];

        if (!$this->menuGenerated) {
            $this->generateCustomMenus();
        }

        return Menu::get($menuName);
    }

    protected function generateCustomMenus()
    {
        $menus = \Clavel\Basic\Models\Menu::get();

        // Cargamos los posibles tipos de menus y sus clases
        $menuPresenters = config("menus.styles");

        // Para cada menu creado en la administración buscamos la correspondiente clase Presenter
        foreach ($menus as $menuValue) {
            Menu::create($menuValue->slug, function ($menu) use ($menuValue, $menuPresenters) {

                // Si existe el presenter, lo cargamos
                if (array_key_exists($menuValue->slug, $menuPresenters)) {
                    $menu->setPresenter($menuPresenters[$menuValue->slug]);
                }

                $this->createMenuItems($menuValue, $menu);

                if (file_exists(app_path('Http/menus.php'))) {
                    require_once app_path('Http/menus.php');
                }
            });
        }

        $this->menuGenerated = true;
    }

    protected function createMenuItems($menuValue, $menu)
    {
        // Leemos todos los elementos del menu ordenados
        foreach ($menuValue->itemsActiveRoot->sortBy('lft') as $menuitem) {
            $this->processMenuItem($menu, $menuitem);
        }
    }

    protected function processMenuItem($menu, $menuitem)
    {
        // Si tiene hijos procesamos el elemento de manera recursiva
        if (MenuItem::withDepth()->defaultOrder()->descendantsOf($menuitem->id)->count()>0) {
            $this->createSubmenuItems($menu, $menuitem);
        } else {
            // El un elemento final por lo que lo presentamos según sus permisos y siempre que no haya sido procesado anteriormente
            if (!in_array($menuitem->id, $this->nodos_precesados)) {
                if (!$menuitem->menuItemType->slug!='pagina' ||
                    ($menuitem->menuItemType->slug=='pagina' && $menuitem->pageAuthorized())) {
                    $this->showMenuItem($menu, $menuitem);
                    // Añadimos el nodo al array de nodos procesados para no volver a procesarlos
                    $this->nodos_precesados[] = $menuitem->id;
                }
            }
        }
    }

    protected function createSubmenuItems($menu, $menuitem)
    {
        if (MenuItem::withDepth()->defaultOrder()->descendantsOf($menuitem->id)->count()>0) {
            $menu->dropdown($this->generateTitle($menuitem), function ($sub) use ($menuitem) {
                $sub->url = $menuitem->generate_url ?? '#';
                foreach (MenuItem::withDepth()->defaultOrder()->descendantsOf($menuitem->id) as $itemChild) {
                    if ($itemChild->status=='1') {
                        $this->processMenuItem($sub, $itemChild);
                    }
                }
            })->hideWhen(function () use ($menuitem) {
                return $menuitem->hiddenMenuPermission();
            });
        }
    }

    protected function showMenuItem($menu, $menuitem)
    {
        if ($menuitem->menuItemType->slug == 'system') {
            // Queremos el punto de menu de Nombre de usuario
            if ($menuitem->module_name == 'profile_name') {
                $menu->url(
                    $menuitem->generate_url,
                    $this->generateTitle($menuitem),
                    ['target' => $menuitem->target]
                )->hideWhen(function () use ($menuitem) {
                    return $menuitem->hiddenMenuPermission();
                });
            } elseif ($menuitem->module_name == 'logout') {
                $menu->route(
                    'logout',
                    $this->generateTitle($menuitem),
                    ['target' => $menuitem->target],
                    [
                        //'icon' => 'fa fa-circle-o',
                        'onclick' => 'event.preventDefault(); document.getElementById(\'logout-form\').submit();'
                    ]
                )->hideWhen(function () use ($menuitem) {
                    return $menuitem->hiddenMenuPermission();
                });
            } elseif ($menuitem->module_name == 'language') {
                // Idioma
                // Este menu saldra si hay multiples idiomas.
                if (Idioma::active()->count() > 1) {
                    $menu->dropdown($menuitem->title, function ($sub) use ($menuitem) {
                        foreach (Idioma::active()->get() as $idioma) {
                            $titulo = '';

                            if ($idioma->code==App::getLocale()) {
                                $titulo .= '<i class="fa fa-dot-circle-o"></i> ';
                            } else {
                                $titulo .= '<i class="fa fa-circle-o"></i> ';
                            }
                            $titulo .= $idioma->translate(App::getLocale())->name;
                            $sub->url(
                                "/changelanguage/".$idioma->code,
                                $titulo,
                                ['target' => $menuitem->target]
                            )->hideWhen(function () use ($menuitem) {
                                return $menuitem->hiddenMenuPermission();
                            });
                        }
                    })
                    //, ['icon' => 'fa fa-circle-o'] Para añadir en un futuro iconos

                    ->hideWhen(function () use ($menuitem) {
                        return $menuitem->hiddenMenuPermission();
                    });
                }
            } elseif ($menuitem->module_name == 'divider') {
                // Divisor
                $menu->divider();
            } else {
                // Otros no controlados
                $menu->url(
                    $menuitem->generate_url,
                    $menuitem->title,
                    ['target' => $menuitem->target]
                )->hideWhen(function () use ($menuitem) {
                    return $menuitem->hiddenMenuPermission();
                });
            }
        } else {
            // El resto
            $menu->url(
                $menuitem->generate_url,
                $menuitem->title,
                ['target' => $menuitem->target]
            )->hideWhen(function () use ($menuitem) {
                return $menuitem->hiddenMenuPermission();
            });
        }
    }

    protected function generateTitle($menuitem)
    {
        if ($menuitem->menuItemType->slug == 'system') {
            if ($menuitem->module_name == 'profile_name') {
                // Si pedimos el nombre del usuario y esta autorizado presentamos el nombre y sino directamente blanco
                if (auth()->check()) {
                    return auth()->user()->userProfile->fullname;
                } else {
                    return '';
                }
            } else {
                return $menuitem->title;
            }
        } else {
            return $menuitem->title;
        }
    }
}
