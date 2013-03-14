<?php

namespace ZfcRbac\Firewall;

class ActionResource extends AbstractFirewall
{
    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        // foreach($rules as $controller => $actions) {
        //     foreach($actions as $action => $resources) {
        //         if (!is_array($resources)) {
        //             $resources = array($resources);
        //         }
        //     }
        // }
        $this->rules=$rules;
    }

    /**
     * Checks if access is granted to resource for the role.
     *
     * @param string $resource
     * @return bool
     */
    public function isGranted($resource)
    {
        $resource   = explode(':', $resource);
        $controller = $resource[0];
        $action     = $resource[1];
        @$requiredPermissions = $this->rules[$controller][$action];
        if (empty($requiredPermissions)) {
            return true;
        }
        if (!is_array($requiredPermissions)) {
            $requiredPermissions = array($requiredPermissions);
        }
        foreach ($requiredPermissions as $permission) {
            if ($this->rbac->isGranted($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the firewall name.
     *
     * @return string
     */
    public function getName()
    {
        return 'actionResource';
    }
}
