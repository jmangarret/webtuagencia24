<?php

			echo (version_compare(JPlatform::RELEASE, '12', 'ge')) ? new JResponseJson() : new JJsonResponse();