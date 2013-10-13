<?php
/**
 * User: Zachary Tong
 * Date: 2013-02-16
 * Time: 09:24 PM
 * Auto-generated by "generate.php"
 * @package Sherlock\components\queries
 */
namespace Sherlock\components\queries;

use Sherlock\components;

/**
 * @method \Sherlock\components\queries\HasParent parent_type() parent_type(\string $value)
 * @method \Sherlock\components\queries\HasParent score_type() score_type(\string $value) Default: "score"
 * @method \Sherlock\components\queries\HasParent query() query(\sherlock\components\QueryInterface $value)

 */
class HasParent extends \Sherlock\components\BaseComponent implements \Sherlock\components\QueryInterface
{
    public function __construct($hashMap = null)
    {
        $this->params['score_type'] = "score";

        parent::__construct($hashMap);
    }

    public function toArray()
    {
        $ret = array (
  'has_parent' =>
  array (
    'parent_type' => $this->params["parent_type"],
    'score_type' => $this->params["score_type"],
    'query' => $this->params["query"]->toArray(),
  ),
);

        return $ret;
    }

}