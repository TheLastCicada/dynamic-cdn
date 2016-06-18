<?php
namespace EAMann\Dynamic_CDN;

/**
 * Companion method for DomainManager object.
 * 
 * @param string $domain
 * 
 * @return DomainManager
 */
function DomainManager( $domain ) {
	return new DomainManager( $domain );
}

class DomainManager {
	/**
	 * @var array Domain to use as a CDN.
	 */
	protected $cdn_domains = array();

	/**
	 * @var bool
	 */
	protected $has_domains = false;

	/**
	 * @var bool Flag to filter only uploaded content.
	 */
	protected $uploads_only = false;

	/**
	 * @var array File extensions to filter.
	 */
	protected $extensions = array();

	/**
	 * @var string
	 */
	protected $site_domain;
	
	public function __construct( $domain ) {
		$this->site_domain = $domain;
	}

	/**
	 * Add a CDN domain to the collection.
	 *
	 * @param string $cdn_domain
	 * 
	 * @return bool
	 */
	public function add( $cdn_domain ) {
		if ( in_array( $cdn_domain, $this->cdn_domains ) ) {
			return false;
		} else {
			$this->cdn_domains[] = $cdn_domain;

			$this->has_domains = true;
			
			return true;
		}
	}

	/**
	 * Get a CDN path for a given file, using a reduced checksum to automatically select from an array of available domains.
	 *
	 * @param string $file_path
	 *
	 * @return string
	 */
	public function cdn_domain( $file_path ) {
		// First, get a checksum for the file path to give us the index we'll use from the CDN domain array.
		$index = abs( crc32( $file_path ) ) % count( $this->cdn_domains );

		/**
		 * Return the correct CDN path to the file.
		 *
		 * @param string $cdn_domain
		 * @param string $file_path
		 */
		return apply_filters( 'dynamic_cdn_domain_for_file', $this->cdn_domains[ $index ], $file_path );
	}
}