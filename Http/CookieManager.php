<?php

namespace Http;
/**
 * Cookie管理器类
 */
class CookieManager
{
    private HttpRequestParameter $parent;
    private array $cookies = array();//存储Cookie的数组
    /**
     * 设置Cookie
     * @param $name string Cookie名称
     * @param $value string Cookie值
     * @return CookieManager
     */
    public function setCookie(string $name, string $value): CookieManager
    {
        // 如果值为 'deleted' 或为空，删除该cookie
        if ($value === 'deleted') {
            unset($this->cookies[$name]);
        } else if($value===''){
            return $this;
        }else{
            $this->cookies[$name] = $value;
        }
        return $this;
    }

    public function __construct()
    {

    }
    /**
     * 设置Cookie字符串
     * @param $string //Cookie字符串
     * @return CookieManager
     */
    public function setCookieString(string $string): CookieManager
    {
        if (empty(trim($string))) {
            return $this; // 空字符串直接返回
        }
        $cookies = array_filter(explode(';', $string), 'trim');
        foreach ($cookies as $cookie) {
            if (!str_contains($cookie, '=')) {
                continue; // 跳过无效 Cookie
            }
            [$name, $value] = array_pad(explode('=', trim($cookie), 2), 2, '');
            $name = trim($name);
            $value = trim($value);
            if ($name !== '') {
                $this->setCookie($name, $value);
            }
        }
        return $this;
    }
    /**
     * 获取所有Cookie字符串
     * @return string Cookie字符串
     * - 如果Cookie为空，返回空字符串
     */
    public function getCookieString(): string
    {
        $cookieString = '';
        foreach ($this->cookies as $name => $value) {
            // 强制类型 + 非空校验
            $name = (string)$name;
            $value = (string)$value;
            /*if ($name === '' || $value === '') {
                continue; // 跳过无效项
            }*/
            // 编码特殊字符
            $cookieString .= rawurlencode($name) . '=' . rawurlencode($value) . '; ';
        }
        return rtrim($cookieString, '; ');
    }

    /**
     * 清空Cookie
     * @return $this
     */
    public function clearCookie(): CookieManager
    {
        $this->cookies = array();
        return $this;
    }
}