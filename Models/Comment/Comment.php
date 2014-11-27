<?php

/**
 * Description of Comment
 *
 * @author libo
 */
class Models_Comment_Comment extends Models_DDClient
{

    /**
     * 添加评论
     * @param string $type 评论类型
     * @param string $id 类型id
     * @param string $content 评论的内容
     * @param string $user_id 用户id
     * @param string $user_name 用户名
     * @param type $reply_id 回复某条评论的评论id
     * @return array
     */
    public function addComment($type, $id, $content, $user_id,$user_name, $reply_id = NULL)
    {
        
        $url = 'comment/addcomment';
        $param = array('type' => $type, 'id' => $id, 'content' => $content, 'sender_id' => $user_id, 'reply_id' => $reply_id,'sender_name' =>$user_name);
        return $this->getDataByApi($url, $param);
    }

    /**
     *
     * @param string $type 评论类型
     * @param string $type_id 类型id
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function commentList($type, $type_id, $start, $limit)
    {
        $url = 'comment/commentlist';
        $param = array('id' => $type_id, 'type' => $type, 'start' => $start, 'count' => $limit);
        return $this->getDataByApi($url, $param);
    }

    /**
     * 取部分评论数据
     * @param string $type 评论类型
     * @param string $type_id 类型id
     * @param int $count 获取评论的数目
     * @return array
     */
    public function partCommentList($type, $type_id, $count)
    {
        $url = 'comment/partcommentlist';
        $param = array('id' => $type_id, 'type' => $type, 'count' => $count);
        return $this->getDataByApi($url, $param);
    }

    /**
     * 删除评论
     * @param $comment_id 评论id
     * @return array
     */
   public function deleteComment($comment_id)
   {
     $url = 'comment/deletecomment';
     $param = array('comment_id' =>$comment_id);
     return  $this->getDataByApi($url,$param);
   }
}
