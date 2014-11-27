<?php

 /**
 * Description of StudyType
 * 学习资源类型
 * @author libo
 */

class Models_StudyResource_StudyType extends Cola_Model
{
   protected $_table = 'resource_type';
   protected $_pk = 'id';
    /**
     * 添加资源分类
     * @param array $data 
     */
   public function addStudyType($data)
   {
    $count = $this->isSameClassifyName($data['classify_name'],$data['node_id'],$data['xk']);
     if(empty($count)){
       return  $this->insert($data,$this->_table); 
     }else{
      return false;
     }
   }

   /**
    * 删除类型
    * @param  int $id 主键id
    * @return bool
    */
   public function deleteTypeById($id)
   {
   		return $this->delete($id);
   }

   /**
    * 更新数据
    * @param  array $updateData 
    * @param  int $id   主键id
    * @return int             
    */
   public function updateData($updateData ,$id)
   {
     return  $this->update($id,$updateData);
   }

  /**
   * 检测分类名称是否相同 
   * @param  string  $classify_name  分类名称
   * @param  string  $xk
   * @param  int  $node_id
   * @return boolean               
 */
    public function isSameClassifyName($classify_name,$node_id,$xk)
    {
      return $this->count("`classify_name` = '{$classify_name}' and `xk` = '{$xk}' and `node_id` = {$node_id}");
    }
    /**
     * 获取某条信息
     * @param  int $id 主键id
     * @return array
     */
    public function getInfoById($id)
    {
      return $this->load($id);
    }
   /**
    * 取类型列表
    * @return array
    */
   public function getTypeList($start,$limit,$node_id = 0,$xk)
   {
   	 if(!empty($node_id)){
   	 	 $sql = "select * from {$this->_table} where `node_id` = $node_id and `xk` = '{$xk}'";
   	 }else{
   	 	$sql = "select * from {$this->_table} where `xk` = '{$xk}'";
   	 }
   	 return $this->getListBySql($sql, $start, $limit);
   }

   /**
    * 获取某年级的资源类型列表
    * @param  int $node_id 
    * @return array  
    */
   public function getTypeListByNodeId($node_id)
   {
      return $this->sql("select * from {$this->_table} where `node_id` = {$node_id}");
   }

  /**
   * 获取某个年级id对应的测评的主键id
   * $node_id 年级id
   */
  public function getStudyTypeIdByGradeNodeId($node_id)
  {
    //获取年级id对应的主键id
    return $this->sql("select `id` from {$this->_table} where `node_id` = {$node_id} and `type_id` = 100 ");
  }
}

?>