<?php

/**
 * manages chunks of html, providing a container for various chunks, the ability
 * to add more on the fly, then providing an output after all logic has been
 * executed.
 *
 * In ways, works similarly to php's output buffer, except this doesn't catch
 * any output.
 *
 * @copyright Copyright (c) 2012 Josh Wickham
 * @class ChunkHelper
 * @since May 9, 2012
 * @author Josh Wickham
 */
class ChunkHelper {
	private static $m_chunkHelpers = array();

	private $m_chunks = array();
	private $m_header = '';
	private $m_footer = '';

	private $m_helperId;

	public function getId () {
		return $this->m_helperId;
	}

	public function setHeader ($header = '') {
		$this->m_header = $header;
	}

	public function setFooter ($footer = '') {
		$this->m_footer = $footer;
	}

	public function addChunk ($chunk = '') {
		$this->m_chunks[] = $chunk;
	}

	public function getComposite ($separator = '') {
		$output = $this->m_header;
		$output .= implode($separator, $this->m_chunks);
		$output .= $this->m_footer;
		return $output;
	}

	/**
	 *
	 * @return ChunkHelper
	 */
	public static function startNew () {
		$id = uniqid();
		self::$m_chunkHelpers[$id] = new ChunkHelper($id);
		return self::$m_chunkHelpers[$id];
	}

	/**
	 * @param type $id
	 * @return ChunkHelper
	 * @throws Exception
	 */
	public static function get ($id) {
		if (!isset(self::$m_chunkHelpers[$id])) {
			throw new Exception('Chunk with id ' . $id . ' could not be found');
		}
		return self::$m_chunkHelpers[$id];
	}

	public static function clear ($id) {
		unset(self::$m_chunkHelpers[$id]);
	}

	private function __construct ($id) {
		$this->m_helperId = $id;
	}
}
