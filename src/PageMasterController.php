<?php
namespace MindOfMicah\PageMaster;

use App\Http\Controllers\Controller;

class PageMasterController extends Controller
{
    use ViewResolverTrait;

    public function show()
    {
        return $this->view();
    }

}
