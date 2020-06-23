<?php
namespace Alek\Storage;


interface StorageEngine{
	public function store_pixel(\Alek\Models\Pixel $pixel);
}