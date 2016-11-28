<?php
/* ========================================================================
 * session 操作类
 * ======================================================================== */
namespace phpcms;

class session
{
    /**
     * 设置一个session值
     * @param string $sessionName 键名
     * @param string $value 键值
     * @return string session的值如果没有此session，返回空。
     */
	function set($sessionName,$value)
    {
		return $_SESSION[$sessionName] = $value;
	}

	/**
	 * 根据sessionName获取session值
	 * @param string $sessionName
	 * @return string session的值如果没有此session，返回空。
	 */
	function get($sessionName)
	{
		return isset($_SESSION[$sessionName]) ? $_SESSION[$sessionName] : '';
	}

	/**
	 * 删除一个session
	 * @param string $sessionName
     * @return boolean true|false
	 */
	function del($sessionName)
	{
		if(isset($sessionName)){
		unset($_SESSION[$sessionName]);
		return TRUE;
		}
		return False;
	}

    /**
     * 清空session
     * @param string $sessionName
     */
	function clear()
	{
		if(isset($_SESSION)){
			session_unset();
		}
	}
}