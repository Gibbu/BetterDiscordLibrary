<?php

function flash($type, $message) {
	session()->flash('flash.type', $type);
	session()->flash('flash.message', $message);
}