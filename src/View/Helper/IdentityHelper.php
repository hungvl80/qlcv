<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

class IdentityHelper extends Helper
{
    protected $_defaultConfig = [];

    public function get(string $key = null)
    {
        $identity = $this->_View->getRequest()->getAttribute('identity');
        if ($identity === null) {
            return null;
        }
        
        return $key === null ? $identity : $identity->get($key);
    }
}