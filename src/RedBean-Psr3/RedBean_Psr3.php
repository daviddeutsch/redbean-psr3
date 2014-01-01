<?php

/**
 * Adopted from
 *
 * https://github.com/php-fig/log/blob/master/Psr/Log/AbstractLogger.php
 */
class RedBean_Psr3
{
	/**
	 * Optional: A RedBean Instance (instead of using the Facade)
	 *
	 * @var null|RedBean_Instance
	 */
	private $db = null;

	/**
	 * Optional:
	 * @var array
	 */
	private $expansions = array();

	const EMERGENCY = 'emergency';
	const ALERT     = 'alert';
	const CRITICAL  = 'critical';
	const ERROR     = 'error';
	const WARNING   = 'warning';
	const NOTICE    = 'notice';
	const INFO      = 'info';
	const DEBUG     = 'debug';

	/**
	 * @param mixed $db   null|RedBean_Instance
	 * @param mixed $keys array|object
	 */
	public function __construct( $db=null, $keys=null )
	{
		if ( !is_null($db) ) {
			$this->db = $db;
		}

		if ( !empty($keys) ) {
			$this->expandContext($keys);
		}
	}

	public function instance( $db=null )
	{
		return new RedBean_Psr3($db);
	}

	public function expandContext( $keys )
	{
		if ( !is_array($keys) ) {
			$keys = array($keys);
		}

		foreach ( $keys as $key ) {
			if ( array_search($key, $this->expansions) !== false ) continue;

			$this->expansions[] = $key;
		}
	}

	public function log( $level, $message, $context=null )
	{
		if ( !is_null($this->db) ) {
			$log = $this->db->dispense('log');
		} else {
			$log = R::dispense('log');
		}

		$log->level = $level;
		$log->message = $message;

		$log = $this->addContext($log, $context);

		if ( !is_null($this->db) ) {
			$this->db->store($log);
		} else {
			R::store($log);
		}
	}

	private function addContext( $log, $context )
	{
		if ( is_string($context) ) {
			$log->context = $context;

			return $log;
		}

		if ( empty($this->expansions) ) {
			$log->context = json_encode($context);

			return $log;
		}

		$context = (array) $context;

		foreach ( $context as $k => $v ) {
			if ( in_array($k, $this->expansions) ) {
				if ( is_string($v) ) {
					$log->$k = $v;
				} else {
					$log->$k = json_encode($context);
				}

				unset($context[$k]);
			}
		}

		if ( !empty($context) ) {
			$log->context = json_encode($context);
		}

		return $log;
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function emergency( $message, array $context = array() )
	{
		$this->log(self::EMERGENCY, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function alert( $message, array $context = array() )
	{
		$this->log(self::ALERT, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function critical( $message, array $context = array() )
	{
		$this->log(self::CRITICAL, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function error( $message, array $context = array() )
	{
		$this->log(self::ERROR, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function warning( $message, array $context = array() )
	{
		$this->log(self::WARNING, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function notice( $message, array $context = array() )
	{
		$this->log(self::NOTICE, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function info( $message, array $context = array() )
	{
		$this->log(self::INFO, $message, $context);
	}

	/**
	 * @param string $message
	 * @param array $context
	 */
	public function debug( $message, array $context = array() )
	{
		$this->log(self::DEBUG, $message, $context);
	}
}
