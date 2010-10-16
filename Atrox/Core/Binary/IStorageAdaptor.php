<?php
/**
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @copyright Clock Limited 2010
 * @version 5.1 - $Revision: 953 $ - $Date: 2009-05-06 15:55:50 +0100 (Wed, 06 May 2009) $
 * @package Core
 */
interface Atrox_Core_Binary_IStorageAdaptor {

	const ACCESS_PRIVATE = 1;
	const ACCESS_PUBLIC = 2;

	public function listBuckets();

	public function makeBucket($name, $accessControl = self::ACCESS_PUBLIC);

	public function getBucket($name);

	public function putBinaryFile($file, $bucketName, $uri, $mimeType = null, $accessControl = self::ACCESS_PUBLIC);

	public function getBinary($bucketName, $uri);

	public function getBinaryUrl($bucketName, $uri);

	public function deleteBinary($bucketName, $uri);

	public function deleteBucket($bucketName);
}