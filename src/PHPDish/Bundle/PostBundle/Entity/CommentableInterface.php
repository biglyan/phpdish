<?php
/**
 * PHPDish comment component
 * @author Tao <taosikai@yeah.net>
 */
namespace PHPDish\Bundle\PostBundle\Entity;

interface CommentableInterface
{
    /**
     * 获取对应的评论类型
     * @return string
     */
    public static function getCommentType();

    /**
     * 获取评论数量
     * @return int
     */
    public function getCommentCount();

    /**
     * 获取所有的评论
     * @return CommentInterface[]
     */
    public function getComments();
}