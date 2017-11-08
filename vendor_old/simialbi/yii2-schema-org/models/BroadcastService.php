<?php

namespace simialbi\yii2\schemaorg\models;

/**
 * Model for BroadcastService
 *
 * @package simialbi\yii2\schemaorg\models
 * @see http://schema.org/BroadcastService
 */
class BroadcastService extends Service {
	/**
	* @var Organization The media network(s) whose content is broadcast on this station.
	*/
	public $broadcastAffiliateOf;

	/**
	* @var string The name displayed in the channel guide. For many US affiliates, it is the network name.
	*/
	public $broadcastDisplayName;

	/**
	* @var string The timezone in ISO 8601 format for which the service bases its broadcasts
	*/
	public $broadcastTimezone;

	/**
	* @var Organization The organization owning or operating the broadcast service.
	*/
	public $broadcaster;

	/**
	* @var BroadcastService A broadcast service to which the broadcast service may belong to such as regional variations of a national channel.
	*/
	public $parentService;

	/**
	* @var string The type of screening or video broadcast used (e.g. IMAX, 3D, SD, HD, etc.).
	*/
	public $videoFormat;

}