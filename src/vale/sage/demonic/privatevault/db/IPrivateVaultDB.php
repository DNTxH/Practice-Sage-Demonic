<?php

namespace vale\sage\demonic\privatevault\db;

interface IPrivateVaultDB
{
	const QUERY_INIT = "core.init";
	const QUERY_LOAD = "core.load";
	const QUERY_SAVE = "core.save";
}