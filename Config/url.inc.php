<?php

///resource/index/xd001-GS0025-v01-GO003-1-6385
//v01,GO003-1,6385
$urlConfig = array(
    '_urls' => array(
        '{^xl$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Index',
            'action' => 'indexAction',
        ),
        '{^sm$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Index',
            'action' => 'indexAction',
        ),
        '{^xl/resource/(\w+)-(\w+)-(.+)-(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Resource',
            'action' => 'indexAction',
            'maps' => array(
                1 => 'xd',
                2 => 'bb',
                3 => 'nj',
                4 => 'zs'
            ),
        ),
        '{^xl/resource/view/(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Resource',
            'action' => 'viewAction',
            'maps' => array(
                1 => 'id',
            ),
        ),
        '{^sm/resource/(\w+)-(\w+)-(.+)-(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Resource',
            'action' => 'indexAction',
            'maps' => array(
                1 => 'xd',
                2 => 'bb',
                3 => 'nj',
                4 => 'zs'
            ),
        ),
        '{^sm/resource/view/(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Resource',
            'action' => 'viewAction',
            'maps' => array(
                1 => 'id',
            ),
        ),
        '{^xl/Study/learin/(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Study',
            'action' => 'learinAction',
            'maps' => array(
                1 => 'resource_id',
            ),
        ),
        '{^sm/Study/learin/(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Study',
            'action' => 'learinAction',
            'maps' => array(
                1 => 'resource_id',
            ),
        ),
        '{^xl/Evaluate/doEvaluate/(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Evaluate',
            'action' => 'doEvaluateAction',
            'maps' => array(
                1 => 'evaluate_id',
            ),
        ),
        '{^sm/Evaluate/doEvaluate/(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Evaluate',
            'action' => 'doEvaluateAction',
            'maps' => array(
                1 => 'evaluate_id',
            ),
        ),
        '{^xl/OnlineStudy/index/(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_OnlineStudy',
            'action' => 'indexAction',
            'maps' => array(
                1 => 'resource_id',
            ),
        ),
         '{^sm/OnlineStudy/index/(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_OnlineStudy',
            'action' => 'indexAction',
            'maps' => array(
                1 => 'resource_id',
            ),
        ),
        // 备课夹中资源搜索
        '{^xl/lesson/resourceSearch/(\w+)-(.+)-(\d+)$}i' => array(
            'controller' => 'Modules_Xl_Controllers_Lesson',
            'action' => 'resourceSearchAction',
            'maps' => array(
                1 => 'bb',
                2 => 'nj',
                3 => 'zs'
            ),
        ),
        '{^sm/lesson/resourceSearch/(\w+)-(.+)-(\d+)$}i' => array(
            'controller' => 'Modules_Sm_Controllers_Lesson',
            'action' => 'resourceSearchAction',
            'maps' => array(
                1 => 'bb',
                2 => 'nj',
                3 => 'zs'
            ),
        ),
    )
);
