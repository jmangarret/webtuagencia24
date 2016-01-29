<?php
class com_catalogo_planesInstallerScript
{
        /**
         * Constructor
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        public function __construct($parent){
		}
 
        /**
         * Called before any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function preflight($route, $parent){
		}
 
        /**
         * Called after any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function postflight($route, $parent){
		}
 
        /**
         * Called on installation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function install($parent){
			$app = JFactory::getApplication();
			$db = JFactory::getDbo();
			$prefix = $app->getCfg('dbprefix');
			$query1="
			/*!50003 CREATE  FUNCTION `CloneCar`(cloned_product_id INT, prefix VARCHAR(100), user_id INT, param_code VARCHAR(100)) RETURNS int(11)
			BEGIN
			DECLARE nuevo_product_id INT;

			INSERT INTO `#__cp_cars_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`)
			SELECT NULL, SUBSTRING(CONCAT_WS(' ', prefix, param_code, `product_name`), 1, 255), param_code, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, CURRENT_TIMESTAMP, user_id, CURRENT_TIMESTAMP, user_id, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`
			FROM `#__cp_cars_info`
			WHERE `product_id` = cloned_product_id;

			SELECT LAST_INSERT_ID() INTO nuevo_product_id;

			IF nuevo_product_id > 0 THEN
				INSERT INTO `#__cp_cars_files` (`product_id`, `file_url`, `ordering`) SELECT nuevo_product_id, `file_url`, `ordering` FROM `#__cp_cars_files` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_cars_delivery_city` (`product_id`, `city_id`) SELECT nuevo_product_id, `city_id` FROM `#__cp_cars_delivery_city` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_cars_tourismtype` (`product_id`, `tourismtype_id`) SELECT nuevo_product_id, `tourismtype_id` FROM `#__cp_cars_tourismtype` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_cars_taxes` (`product_id`, `tax_id`) SELECT nuevo_product_id, `tax_id` FROM `#__cp_cars_taxes` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_cars_param1` (`product_id`, `param1_id`) SELECT nuevo_product_id, `param1_id` FROM `#__cp_cars_param1` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_cars_param2` (`product_id`, `param2_id`) SELECT nuevo_product_id, `param2_id` FROM `#__cp_cars_param2` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_cars_supplement` (`product_id`, `supplement_id`) SELECT nuevo_product_id, `supplement_id` FROM `#__cp_cars_supplement` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_cars_supplement_tax` (`product_id`, `supplement_id`, `tax_id`) SELECT nuevo_product_id, `supplement_id`, `tax_id` FROM `#__cp_cars_supplement_tax` WHERE `product_id` = cloned_product_id;
			END IF;

			RETURN nuevo_product_id;
			END */";$db->setQuery($query1);$db->query($query1); $query2="

			/*!50003 CREATE  FUNCTION `CloneHotel`(cloned_product_id INT, prefix VARCHAR(100), user_id INT, param_code VARCHAR(100)) RETURNS int(11)
			BEGIN
			DECLARE nuevo_product_id INT;

			INSERT INTO `#__cp_hotels_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `stars`, `average_rating`, `additional_description`, `disclaimer`) 
			SELECT NULL, SUBSTRING(CONCAT_WS(' ', prefix, param_code, `product_name`), 1, 255), param_code, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, CURRENT_TIMESTAMP, user_id, CURRENT_TIMESTAMP, user_id, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `stars`, 0, `additional_description`, `disclaimer`
			FROM `#__cp_hotels_info` 
			WHERE `product_id` = cloned_product_id;

			SELECT LAST_INSERT_ID() INTO nuevo_product_id;

			IF nuevo_product_id > 0 THEN
				
				INSERT INTO `#__cp_hotels_files` (`product_id`, `file_url`, `ordering`) SELECT nuevo_product_id, `file_url`, `ordering` FROM `#__cp_hotels_files` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_hotels_tourismtype` (`product_id`, `tourismtype_id`) SELECT nuevo_product_id, `tourismtype_id` FROM `#__cp_hotels_tourismtype` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_hotels_taxes` (`product_id`, `tax_id`) SELECT nuevo_product_id, `tax_id` FROM `#__cp_hotels_taxes` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_hotels_amenity` (`product_id`, `amenity_id`) SELECT nuevo_product_id, `amenity_id` FROM `#__cp_hotels_amenity` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_hotels_param1` (`product_id`, `param1_id`) SELECT nuevo_product_id, `param1_id` FROM `#__cp_hotels_param1` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_hotels_param2` (`product_id`, `param2_id`) SELECT nuevo_product_id, `param2_id` FROM `#__cp_hotels_param2` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_hotels_param3` (`product_id`, `param3_id`) SELECT nuevo_product_id, `param3_id` FROM `#__cp_hotels_param3` WHERE `product_id` = cloned_product_id;

				
				INSERT INTO `#__cp_hotels_supplement` (`product_id`, `supplement_id`, `apply_once`) SELECT nuevo_product_id, `supplement_id`, `apply_once` FROM `#__cp_hotels_supplement` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_hotels_supplement_tax` (`product_id`, `supplement_id`, `tax_id`) SELECT nuevo_product_id, `supplement_id`, `tax_id` FROM `#__cp_hotels_supplement_tax` WHERE `product_id` = cloned_product_id;
			END IF;

			RETURN nuevo_product_id;

			END */";$db->setQuery($query2);$db->query($query2); $query3="

			/*!50003 CREATE  FUNCTION `ClonePlan`(cloned_product_id INT, prefix VARCHAR(100), user_id INT, param_code VARCHAR(100)) RETURNS int(11)
			BEGIN
			DECLARE nuevo_product_id INT;

			INSERT INTO `#__cp_plans_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `duration`, `days_total`, `average_rating`, `additional_description`, `disclaimer`) 
			SELECT NULL, SUBSTRING(CONCAT_WS(' ', prefix, param_code,`product_name`), 1, 255), param_code, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, CURRENT_TIMESTAMP, user_id, CURRENT_TIMESTAMP, user_id, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `duration`, `days_total`, 0, `additional_description`, `disclaimer` 
			FROM `#__cp_plans_info` 
			WHERE `product_id` = cloned_product_id;

			SELECT LAST_INSERT_ID() INTO nuevo_product_id;


			IF nuevo_product_id > 0 THEN
				
				INSERT INTO `#__cp_plans_files` (`product_id`, `file_url`, `ordering`) SELECT nuevo_product_id, `file_url`, `ordering` FROM `#__cp_plans_files` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_plans_tourismtype` (`product_id`, `tourismtype_id`) SELECT nuevo_product_id, `tourismtype_id` FROM `#__cp_plans_tourismtype` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_plans_taxes` (`product_id`, `tax_id`) SELECT nuevo_product_id, `tax_id` FROM `#__cp_plans_taxes` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_plans_param1` (`product_id`, `param1_id`) SELECT nuevo_product_id, `param1_id` FROM `#__cp_plans_param1` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_plans_param2` (`product_id`, `param2_id`) SELECT nuevo_product_id, `param2_id` FROM `#__cp_plans_param2` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_plans_param3` (`product_id`, `param3_id`) SELECT nuevo_product_id, `param3_id` FROM `#__cp_plans_param3` WHERE `product_id` = cloned_product_id;
				
				INSERT INTO `#__cp_plans_supplement` (`product_id`, `supplement_id`) SELECT nuevo_product_id, `supplement_id` FROM `#__cp_plans_supplement` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_plans_supplement_tax` (`product_id`, `supplement_id`, `tax_id`) SELECT nuevo_product_id, `supplement_id`, `tax_id` FROM `#__cp_plans_supplement_tax` WHERE `product_id` = cloned_product_id;
			END IF;

			RETURN nuevo_product_id;

			END */";$db->setQuery($query3);$db->query($query3); $query4="

			/*!50003 CREATE  FUNCTION `CloneTour`(cloned_product_id INT, prefix VARCHAR(100), user_id INT, param_code VARCHAR(100)) RETURNS int(11)
			BEGIN
			DECLARE nuevo_product_id INT;

			INSERT INTO `#__cp_tours_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`)
			SELECT NULL, SUBSTRING(CONCAT_WS(' ', prefix, param_code, `product_name`), 1, 255), param_code, `product_desc`, `category_id`, `country_id`, `region_id`, `city_id`, `zone_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, CURRENT_TIMESTAMP, user_id, CURRENT_TIMESTAMP, user_id, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`
			FROM `#__cp_tours_info`
			WHERE `product_id` = cloned_product_id;

			SELECT LAST_INSERT_ID() INTO nuevo_product_id;

			IF nuevo_product_id > 0 THEN
				INSERT INTO `#__cp_tours_files` (`product_id`, `file_url`, `ordering`) SELECT nuevo_product_id, `file_url`, `ordering` FROM `#__cp_tours_files` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_tours_delivery_city` (`product_id`, `city_id`) SELECT nuevo_product_id, `city_id` FROM `#__cp_tours_delivery_city` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_tours_tourismtype` (`product_id`, `tourismtype_id`) SELECT nuevo_product_id, `tourismtype_id` FROM `#__cp_tours_tourismtype` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_tours_taxes` (`product_id`, `tax_id`) SELECT nuevo_product_id, `tax_id` FROM `#__cp_tours_taxes` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_tours_param1` (`product_id`, `param1_id`) SELECT nuevo_product_id, `param1_id` FROM `#__cp_tours_param1` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_tours_param2` (`product_id`, `param2_id`) SELECT nuevo_product_id, `param2_id` FROM `#__cp_tours_param2` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_tours_supplement` (`product_id`, `supplement_id`) SELECT nuevo_product_id, `supplement_id` FROM `#__cp_tours_supplement` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_tours_supplement_tax` (`product_id`, `supplement_id`, `tax_id`) SELECT nuevo_product_id, `supplement_id`, `tax_id` FROM `#__cp_tours_supplement_tax` WHERE `product_id` = cloned_product_id;
			END IF;

			RETURN nuevo_product_id;

			END */";$db->setQuery($query4);$db->query($query4); $query5="

			/*!50003 CREATE  FUNCTION `CloneTransfer`(cloned_product_id INT, prefix VARCHAR(100), user_id INT, param_code VARCHAR(100)) RETURNS int(11)
			BEGIN
			DECLARE nuevo_product_id INT;

			INSERT INTO `#__cp_transfers_info` (`product_id`, `product_name`, `product_code`, `product_desc`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, `created`, `created_by`, `modified`, `modified_by`, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, `average_rating`, `additional_description`, `disclaimer`) 
			SELECT NULL, SUBSTRING(CONCAT_WS(' ', prefix, param_code,`product_name`), 1, 255), param_code, `product_desc`, `country_id`, `region_id`, `city_id`, `featured`, `latitude`, `longitude`, `ordering`, `publish_up`, `publish_down`, CURRENT_TIMESTAMP, user_id, CURRENT_TIMESTAMP, user_id, `access`, `published`, `tag_name1`, `tag_content1`, `tag_name2`, `tag_content2`, `tag_name3`, `tag_content3`, `tag_name4`, `tag_content4`, `tag_name5`, `tag_content5`, `tag_name6`, `tag_content6`, `product_url`, `supplier_id`, 0, `additional_description`, `disclaimer` 
			FROM `#__cp_transfers_info` 
			WHERE `product_id` = cloned_product_id;

			SELECT LAST_INSERT_ID() INTO nuevo_product_id;

			IF nuevo_product_id > 0 THEN
				INSERT INTO `#__cp_transfers_category` (`product_id`, `category_id`) SELECT nuevo_product_id, `category_id` FROM `#__cp_transfers_category` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_transfers_files` (`product_id`, `file_url`, `ordering`) SELECT nuevo_product_id, `file_url`, `ordering` FROM `#__cp_transfers_files` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_transfers_taxes` (`product_id`, `tax_id`) SELECT nuevo_product_id, `tax_id` FROM `#__cp_transfers_taxes` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_transfers_param1` (`product_id`, `param1_id`) SELECT nuevo_product_id, `param1_id` FROM `#__cp_transfers_param1` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_transfers_param2` (`product_id`, `param2_id`) SELECT nuevo_product_id, `param2_id` FROM `#__cp_transfers_param2` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_transfers_param3` (`product_id`, `param3_id`) SELECT nuevo_product_id, `param3_id` FROM `#__cp_transfers_param3` WHERE `product_id` = cloned_product_id;

				INSERT INTO `#__cp_transfers_supplement` (`product_id`, `supplement_id`) SELECT nuevo_product_id, `supplement_id` FROM `#__cp_transfers_supplement` WHERE `product_id` = cloned_product_id;
				INSERT INTO `#__cp_transfers_supplement_tax` (`product_id`, `supplement_id`, `tax_id`) SELECT nuevo_product_id, `supplement_id`, `tax_id` FROM `#__cp_transfers_supplement_tax` WHERE `product_id` = cloned_product_id;
			END IF;

			RETURN nuevo_product_id;

			END */";$db->setQuery($query5);$db->query($query5); $query6="
			
			/*!50003 CREATE  PROCEDURE `BookingCar`(
			  stock_product_id INT,
			  start_date DATE,
			  end_date DATE,
			  stock_quantity INT
			)
			BEGIN

				DECLARE total_day INT;
				DECLARE stock_day INT;
				DECLARE x DATE;
				DECLARE count_day INT;


				SELECT
				  count(hs.day) INTO total_day
				FROM #__cp_cars_stock hs
				WHERE
				  product_id=stock_product_id
				  AND hs.day between start_date AND end_date
				  AND hs.quantity >=stock_quantity
				GROUP BY param_id;

				SET count_day = 0;
				
				if(total_day =(DATEDIFF(end_date, start_date)+1)) THEN
					
					SET x = start_date;
					
					WHILE x <= end_date DO
						
						SELECT quantity INTO stock_day
						FROM #__cp_cars_stock
						WHERE
						  product_id = stock_product_id
						  AND `day` = x;

						SET stock_day = stock_day-stock_quantity;

						IF(stock_day>0)THEN
						  
						  UPDATE
							#__cp_cars_stock
						  SET quantity=stock_day
						  WHERE product_id = stock_product_id AND `day` = x;
						ELSE
						  
						  DELETE FROM #__cp_cars_stock
						  WHERE product_id = stock_product_id AND `day` = x;
						END IF;

						SET count_day = count_day+1;
						SET x = ADDDATE(x, 1);
					END WHILE;
				END IF;
				
				IF(count_day=DATEDIFF(end_date,start_date)+1) THEN
				  select 'true' as result;
				ELSE
				  select 'false' as result;
				END IF;

			END */";$db->setQuery($query6);$db->query($query6); $query7="

			/*!50003 CREATE  PROCEDURE `BookingHotel`(
			  stock_product_id INT,
			  stock_param_id INT,
			  start_date DATE,
			  end_date DATE,
			  stock_quantity INT
			)
			BEGIN
				DECLARE total_day INT;
				DECLARE stock_day INT;
				DECLARE x DATE;
				DECLARE count_day INT;
				
				SELECT
				  count(hs.day) INTO total_day
				FROM #__cp_hotels_stock hs
				WHERE
				  product_id=stock_product_id
				  AND hs.day between start_date AND end_date
				  AND hs.param_id =stock_param_id
				  AND hs.quantity >=stock_quantity
				GROUP BY param_id;

				SET count_day = 0;
				
				if(total_day =(DATEDIFF(end_date, start_date)+1)) THEN
					
					SET x = start_date;

					
					WHILE x <= end_date DO
						
						SELECT quantity INTO stock_day
						FROM #__cp_hotels_stock
						WHERE
						  product_id = stock_product_id
						  AND param_id = stock_param_id
						  AND `day` = x;

						SET stock_day = stock_day-stock_quantity;

						IF(stock_day>0)THEN
						  
						  UPDATE
							#__cp_hotels_stock
						  SET quantity=stock_day
						  WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
						ELSE

						  DELETE FROM #__cp_hotels_stock
						  WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
						END IF;

						SET count_day = count_day+1;
						SET x = ADDDATE(x, 1);
					END WHILE;
				END IF;
				
				IF(count_day=(DATEDIFF(end_date,start_date)+1)) THEN
				  select 'true' as result;
				ELSE
				  select 'false' as result;
				END IF;

			END */";$db->setQuery($query7);$db->query($query7); $query8="

			/*!50003 CREATE  PROCEDURE `BookingPlan`(
			  stock_product_id INT,
			  stock_param_id INT,
			  start_date DATE,
			  stock_quantity INT

			)
			BEGIN
				DECLARE total_day INT;
				DECLARE stock_day INT;
				DECLARE count_day INT;
				
				SELECT quantity INTO stock_day
				FROM #__cp_plans_stock
				WHERE
				  product_id = stock_product_id
				  AND param_id = stock_param_id
						AND `day` = start_date;
				
				IF stock_day>=stock_quantity THEN
				  SET stock_day = stock_day-stock_quantity;
				  IF(stock_day>0)THEN
					UPDATE
					  #__cp_plans_stock
					SET quantity=stock_day
					WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = start_date;
				  ELSE
					DELETE FROM #__cp_plans_stock
					WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = start_date;
				  END IF;
				  select 'true' as result;
				ELSE
				  select 'false' as result;

				END IF;

			END */";$db->setQuery($query8);$db->query($query8); $query9="

			
			/*!50003 CREATE  PROCEDURE `BookingTour`(
			  stock_product_id INT,
			  start_date DATE,
			  stock_quantity INT

			)
			BEGIN

				DECLARE total_day INT;
				DECLARE stock_day INT;
				DECLARE count_day INT;

				
				SELECT quantity INTO stock_day
				FROM #__cp_tours_stock
				WHERE
				  product_id = stock_product_id
						AND `day` = start_date;

				
				IF stock_day>=stock_quantity THEN

				  SET stock_day = stock_day-stock_quantity;

				  IF(stock_day>0)THEN

					UPDATE
					  #__cp_tours_stock
					SET quantity=stock_day
					WHERE product_id = stock_product_id AND `day` = start_date;
				  ELSE

					DELETE FROM #__cp_tours_stock
					WHERE product_id = stock_product_id AND `day` = start_date;
				  END IF;

				  select 'true' as result;

				ELSE

				  select 'false' as result;


				END IF;

			END */";$db->setQuery($query9);$db->query($query9); $query10="


			/*!50003 CREATE  PROCEDURE `BookingTransfer`(
			  stock_product_id INT,
			  stock_param_id INT,
			  start_date DATE,
			  end_date DATE,
			  stock_quantity INT,
			  param_type INT

			)
			BEGIN


				DECLARE total_day INT;
				DECLARE stock_day INT;
				DECLARE x DATE;
				DECLARE count_day INT;

				
				SELECT
				  count(distinct(hs.day)) INTO total_day
				FROM #__cp_transfers_stock hs
				WHERE
				  product_id=stock_product_id
				  AND if(param_type=1,hs.day = start_date,hs.day = start_date OR hs.day = end_date)
				  AND hs.param_id =stock_param_id
				  AND hs.quantity >=stock_quantity
				GROUP BY param_id;

				SET count_day = 0;

				if(total_day =param_type) THEN


						SELECT quantity INTO stock_day
						FROM #__cp_transfers_stock
						WHERE
						  product_id = stock_product_id
						  AND param_id = stock_param_id
						  AND `day` = start_date;


						SET stock_day = stock_day-stock_quantity;

						IF(stock_day>0)THEN

						  UPDATE
							#__cp_transfers_stock
						  SET quantity=stock_day
						  WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = start_date;
						ELSE

						  DELETE FROM #__cp_transfers_stock
						  WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = start_date;
						END IF;

						SET count_day = count_day+1;

				  IF(param_type=2) THEN

					SELECT quantity INTO stock_day
					FROM #__cp_transfers_stock
					WHERE
					  product_id = stock_product_id
					  AND param_id = stock_param_id
					  AND `day` = end_date;


					  SET stock_day = stock_day-stock_quantity;

					  IF(stock_day>0)THEN

						UPDATE
						  #__cp_transfers_stock
						SET quantity=stock_day
						WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = end_date;
					  ELSE

						DELETE FROM #__cp_transfers_stock
						WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = end_date;
					  END IF;

					  SET count_day = count_day+1;

				  END IF;


				END IF;


				IF(count_day=param_type) THEN
				  select 'true' as result;
				ELSE
				  select 'false' as result;
				END IF;


			END */";$db->setQuery($query10);$db->query($query10); $query11="


			/*!50003 CREATE  PROCEDURE `CleanCarStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE)
				COMMENT 'Limpia el inventario del producto en un rango de fechas'
			BEGIN

				
				IF stock_param_id > 0 THEN
					DELETE FROM #__cp_cars_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` BETWEEN start_date AND end_date;
				ELSE
					DELETE FROM #__cp_cars_stock WHERE product_id = stock_product_id AND `day` BETWEEN start_date AND end_date;
				END IF;
			END */";$db->setQuery($query11);$db->query($query11); $query12="


			/*!50003 CREATE  PROCEDURE `CleanHotelStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE)
				COMMENT 'Limpia el inventario del producto en un rango de fechas'
			BEGIN

				
				IF stock_param_id > 0 THEN
					DELETE FROM #__cp_hotels_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` BETWEEN start_date AND end_date;
				ELSE
					DELETE FROM #__cp_hotels_stock WHERE product_id = stock_product_id AND `day` BETWEEN start_date AND end_date;
				END IF;
			END */";$db->setQuery($query12);$db->query($query12); $query13="


			/*!50003 CREATE  PROCEDURE `CleanPlanStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE)
				COMMENT 'Limpia el inventario del producto en un rango de fechas'
			BEGIN

				
				IF stock_param_id > 0 THEN
					DELETE FROM #__cp_plans_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` BETWEEN start_date AND end_date;
				ELSE
					DELETE FROM #__cp_plans_stock WHERE product_id = stock_product_id AND `day` BETWEEN start_date AND end_date;
				END IF;
			END */";$db->setQuery($query13);$db->query($query13); $query14="


			/*!50003 CREATE  PROCEDURE `CleanTourStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE)
				COMMENT 'Limpia el inventario del producto en un rango de fechas'
			BEGIN

				
				IF stock_param_id > 0 THEN
					DELETE FROM #__cp_tours_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` BETWEEN start_date AND end_date;
				ELSE
					DELETE FROM #__cp_tours_stock WHERE product_id = stock_product_id AND `day` BETWEEN start_date AND end_date;
				END IF;
			END */";$db->setQuery($query14);$db->query($query14); $query15="


			/*!50003 CREATE  PROCEDURE `CleanTransferStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE)
				COMMENT 'Limpia el inventario del producto en un rango de fechas'
			BEGIN

				
				IF stock_param_id > 0 THEN
					DELETE FROM #__cp_transfers_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` BETWEEN start_date AND end_date;
				ELSE
					DELETE FROM #__cp_transfers_stock WHERE product_id = stock_product_id AND `day` BETWEEN start_date AND end_date;
				END IF;
			END */";$db->setQuery($query15);$db->query($query15); $query16="


			/*!50003 CREATE  PROCEDURE `DeleteCar`(deleted_product_id INT)
			BEGIN

				DELETE s FROM #__cp_cars_rate_supplement s INNER JOIN #__cp_cars_rate r ON s.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE p FROM #__cp_cars_rate_price p INNER JOIN #__cp_cars_rate r ON p.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE FROM #__cp_cars_rate WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_cars_supplement_tax WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_supplement WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_cars_taxes WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_stock WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_resume WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_cars_param1 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_param2 WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_cars_comments WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_delivery_city WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_files WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_cars_info WHERE product_id = deleted_product_id;

			END */";$db->setQuery($query16);$db->query($query16); $query17="


			/*!50003 CREATE  PROCEDURE `DeleteHotel`(deleted_product_id INT)
			BEGIN

				DELETE s FROM #__cp_hotels_rate_supplement s INNER JOIN #__cp_hotels_rate r ON s.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE p FROM #__cp_hotels_rate_price p INNER JOIN #__cp_hotels_rate r ON p.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_rate WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_hotels_supplement_tax WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_supplement WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_hotels_taxes WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_stock WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_resume WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_hotels_param1 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_param2 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_param3 WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_hotels_amenity WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_comments WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_tourismtype WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_files WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_hotels_info WHERE product_id = deleted_product_id;

			END */";$db->setQuery($query17);$db->query($query17); $query18="


			/*!50003 CREATE  PROCEDURE `DeletePlan`(deleted_product_id INT)
			BEGIN

				DELETE s FROM #__cp_plans_rate_supplement s INNER JOIN #__cp_plans_rate r ON s.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE p FROM #__cp_plans_rate_price p INNER JOIN #__cp_plans_rate r ON p.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE FROM #__cp_plans_rate WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_plans_supplement_tax WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_supplement WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_plans_taxes WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_stock WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_resume WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_plans_param1 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_param2 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_param3 WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_plans_comments WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_tourismtype WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_files WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_plans_info WHERE product_id = deleted_product_id;

			END */";$db->setQuery($query18);$db->query($query18); $query19="


			/*!50003 CREATE  PROCEDURE `DeleteTour`(deleted_product_id INT)
			BEGIN

				DELETE s FROM #__cp_tours_rate_supplement s INNER JOIN #__cp_tours_rate r ON s.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE p FROM #__cp_tours_rate_price p INNER JOIN #__cp_tours_rate r ON p.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE FROM #__cp_tours_rate WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_tours_supplement_tax WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_supplement WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_tours_taxes WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_stock WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_resume WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_tours_param1 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_param2 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_param3 WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_tours_comments WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_tourismtype WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_files WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_tours_info WHERE product_id = deleted_product_id;

			END */";$db->setQuery($query19);$db->query($query19); $query20="


			/*!50003 CREATE  PROCEDURE `DeleteTransfer`(deleted_product_id INT)
			BEGIN

				DELETE s FROM #__cp_transfers_rate_supplement s INNER JOIN #__cp_transfers_rate r ON s.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE p FROM #__cp_transfers_rate_price p INNER JOIN #__cp_transfers_rate r ON p.rate_id = r.rate_id WHERE r.product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_rate WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_transfers_supplement_tax WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_supplement WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_transfers_taxes WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_stock WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_resume WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_transfers_param1 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_param2 WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_param3 WHERE product_id = deleted_product_id;

				DELETE FROM #__cp_transfers_category WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_comments WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_files WHERE product_id = deleted_product_id;
				DELETE FROM #__cp_transfers_info WHERE product_id = deleted_product_id;

			END */";$db->setQuery($query20);$db->query($query20); $query21="


			/*!50003 CREATE  PROCEDURE `DetailsCar`(
			  IN param_productId int,
			  IN lang varchar(2)

			)
			BEGIN

			  SELECT
				p.*,
				IFNULL(jfco.value,co.country_name) as country_name,
				co.country_code,
				IFNULL(jfci.value,ci.city_name) as city_name,
				ci.city_code,
				IFNULL(jfr.value,r.region_name) as region_name,
				r.region_code
			  FROM #__cp_cars_info p
				JOIN (#__cp_prm_country co
				   LEFT JOIN (#__jf_content jfco
					 INNER JOIN #__languages lco
					 ON lco.lang_id=jfco.language_id AND lco.sef = lang)
				   ON (co.country_id=jfco.reference_id AND jfco.reference_table = 'cp_prm_country' AND jfco.published=1)
				)
				ON co.country_id=p.country_id
				JOIN (#__cp_prm_city ci
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (ci.city_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_city' AND jfci.published=1)
				)
				ON ci.city_id=p.city_id
				LEFT JOIN (#__cp_prm_region r
				  LEFT JOIN (#__jf_content jfr
					 INNER JOIN #__languages lr
					 ON lr.lang_id=jfr.language_id AND lr.sef = lang)
				   ON (r.region_id=jfr.reference_id AND jfr.reference_table = 'cp_prm_region' AND jfr.published=1)
				)
				ON r.region_id=p.region_id
			  WHERE
				p.product_id = param_productId;

			END */";$db->setQuery($query21);$db->query($query21); $query22="


			/*!50003 CREATE  PROCEDURE `DetailsHotel`(
			  IN param_productId int,
			  IN lang varchar(2)
			)
			BEGIN

			  SELECT
				p.*,
				IFNULL(jfco.value,co.country_name) as country_name,
				co.country_code,
				IFNULL(jfci.value,ci.city_name) as city_name,
				ci.city_code,
				IFNULL(jfr.value,r.region_name) as region_name,
				r.region_code
			  FROM #__cp_hotels_info p
				JOIN (#__cp_prm_country co
				   LEFT JOIN (#__jf_content jfco
					 INNER JOIN #__languages lco
					 ON lco.lang_id=jfco.language_id AND lco.sef = lang)
				   ON (co.country_id=jfco.reference_id AND jfco.reference_table = 'cp_prm_country' AND jfco.published=1)
				)
				ON co.country_id=p.country_id
				JOIN (#__cp_prm_city ci
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (ci.city_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_city' AND jfci.published=1)
				)
				ON ci.city_id=p.city_id
				LEFT JOIN (#__cp_prm_region r
				  LEFT JOIN (#__jf_content jfr
					 INNER JOIN #__languages lr
					 ON lr.lang_id=jfr.language_id AND lr.sef = lang)
				   ON (r.region_id=jfr.reference_id AND jfr.reference_table = 'cp_prm_region' AND jfr.published=1)
				)
				ON r.region_id=p.region_id
			  WHERE
				p.product_id = param_productId;


			END */";$db->setQuery($query22);$db->query($query22); $query23="


			/*!50003 CREATE  PROCEDURE `DetailsPlan`(
			  IN param_productId int,
			  IN lang varchar(2)
			)
			BEGIN


			  SELECT
				p.*,
				IFNULL(jfco.value,co.country_name) as country_name,
				co.country_code,
				IFNULL(jfci.value,ci.city_name) as city_name,
				ci.city_code,
				IFNULL(jfr.value,r.region_name) as region_name,
				r.region_code,
				GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype
			  FROM #__cp_plans_info p
				JOIN (#__cp_prm_country co
				   LEFT JOIN (#__jf_content jfco
					 INNER JOIN #__languages lco
					 ON lco.lang_id=jfco.language_id AND lco.sef = lang)
				   ON (co.country_id=jfco.reference_id AND jfco.reference_table = '#__cp_prm_country' AND jfco.published=1)
				)
				ON co.country_id=p.country_id
				JOIN (#__cp_prm_city ci
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (ci.city_id=jfci.reference_id AND jfci.reference_table = '#__cp_prm_city' AND jfci.published=1)
				)
				ON ci.city_id=p.city_id
				LEFT JOIN (#__cp_prm_region r
				  LEFT JOIN (#__jf_content jfr
					 INNER JOIN #__languages lr
					 ON lr.lang_id=jfr.language_id AND lr.sef = lang)
				   ON (r.region_id=jfr.reference_id AND jfr.reference_table = '#__cp_prm_region' AND jfr.published=1)
				)
				ON r.region_id=p.region_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
			  WHERE
				p.product_id = param_productId
			  GROUP BY p.product_id;
			END */";$db->setQuery($query23);$db->query($query23); $query24="


			/*!50003 CREATE  PROCEDURE `DetailsTour`(
			  IN param_productId int,
			  IN lang varchar(2)
			)
			BEGIN


			  SELECT
				t.*,
				IFNULL(jfco.value,co.country_name) as country_name,
				co.country_code,
				IFNULL(jfci.value,ci.city_name) as city_name,
				ci.city_code,
				IFNULL(jfr.value,r.region_name) as region_name,
				r.region_code,
				GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype
			  FROM #__cp_tours_info t
				JOIN (#__cp_prm_country co
				   LEFT JOIN (#__jf_content jfco
					 INNER JOIN #__languages lco
					 ON lco.lang_id=jfco.language_id AND lco.sef = lang)
				   ON (co.country_id=jfco.reference_id AND jfco.reference_table = '#__cp_prm_country' AND jfco.published=1)
				)
				ON co.country_id=t.country_id
				JOIN (#__cp_prm_city ci
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (ci.city_id=jfci.reference_id AND jfci.reference_table = '#__cp_prm_city' AND jfci.published=1)
				)
				ON ci.city_id=t.city_id
				LEFT JOIN (#__cp_prm_region r
				  LEFT JOIN (#__jf_content jfr
					 INNER JOIN #__languages lr
					 ON lr.lang_id=jfr.language_id AND lr.sef = lang)
				   ON (r.region_id=jfr.reference_id AND jfr.reference_table = '#__cp_prm_region' AND jfr.published=1)
				)
				ON r.region_id=t.region_id
				LEFT JOIN #__cp_tours_tourismtype pt ON pt.product_id=t.product_id
			  WHERE
				t.product_id = param_productId
			  GROUP BY t.product_id;
			END */";$db->setQuery($query24);$db->query($query24); $query25="


			/*!50003 CREATE  PROCEDURE `DetailsTransfer`(
			  IN param_productId int,
			  IN lang varchar(2)
			)
			BEGIN



			  SELECT
				p.*,
				IFNULL(jfco.value,co.country_name) as country_name,
				co.country_code,
				IFNULL(jfci.value,ci.city_name) as city_name,
				ci.city_code,
				IFNULL(jfr.value,r.region_name) as region_name,
				r.region_code
			  FROM #__cp_transfers_info p
				JOIN (#__cp_prm_country co
				   LEFT JOIN (#__jf_content jfco
					 INNER JOIN #__languages lco
					 ON lco.lang_id=jfco.language_id AND lco.sef = lang)
				   ON (co.country_id=jfco.reference_id AND jfco.reference_table = 'cp_prm_country' AND jfco.published=1)
				)
				ON co.country_id=p.country_id
				JOIN (#__cp_prm_city ci
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (ci.city_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_city' AND jfci.published=1)
				)
				ON ci.city_id=p.city_id
				LEFT JOIN (#__cp_prm_region r
				  LEFT JOIN (#__jf_content jfr
					 INNER JOIN #__languages lr
					 ON lr.lang_id=jfr.language_id AND lr.sef = lang)
				   ON (r.region_id=jfr.reference_id AND jfr.reference_table = 'cp_prm_region' AND jfr.published=1)
				)
				ON r.region_id=p.region_id
			  WHERE
				p.product_id = param_productId;


			END */";$db->setQuery($query25);$db->query($query25); $query26="
			
			/*!50003 CREATE  PROCEDURE `FindCar`(
			  IN apply_markup int,
			  IN prm_region_id int,
			  IN prm_city_id int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_category_id int,
			  IN prm_supplier_id int,
			  IN prm_total_dias int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN


			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.latitude,
				  p.longitude,
				  p.zone_id,
				  p.featured,
				  p.category_id,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  count(distinct(pre.date)),
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_cars_info p
				JOIN #__cp_cars_resume pre ON p.product_id=pre.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_cars_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='cars' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_supplier_id=0,1,p.supplier_id=prm_supplier_id)
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='cars' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  

				GROUP BY p.product_id
				having count(distinct(pre.date)) = prm_total_dias  

				ORDER BY p.ordering;
			  ELSE
				SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.latitude,
				  p.longitude,
				  p.zone_id,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  count(distinct(pre.date)),
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  0 as markup
				FROM  #__cp_cars_info p
				JOIN #__cp_cars_resume pre ON p.product_id=pre.product_id

				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_cars_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id)
				  AND if(prm_supplier_id=0,1,p.supplier_id=prm_supplier_id)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  

				GROUP BY p.product_id
				having count(distinct(pre.date)) = prm_total_dias

				ORDER BY p.ordering;

			  END IF;

			END */";$db->setQuery($query26);$db->query($query26); $query27="


			/*!50003 CREATE  PROCEDURE `FindCarCities`(
			  IN apply_markup int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_total_dias int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN



			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_cars_info p
				JOIN #__cp_cars_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_cars_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__cp_cars_files pf ON pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='cars' AND idagency_group=prm_group_id
				WHERE p.published=1
				  AND co.published=1
				  AND ci.published=1
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='cars' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  

				GROUP BY ci.city_id
				having count(distinct(pre.date)) = prm_total_dias  

				ORDER BY p.ordering;

			  ELSE



				SELECT
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  0 as markup
				FROM  #__cp_cars_info p
				JOIN #__cp_cars_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_cars_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__cp_cars_files pf ON pf.product_id=p.product_id
				WHERE p.published=1
				  AND co.published=1
				  AND ci.published=1
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date


				GROUP BY ci.city_id
				having count(distinct(pre.date)) = prm_total_dias

				ORDER BY p.ordering;

			  END IF;


			END */";$db->setQuery($query27);$db->query($query27); $query28="

			
			/*!50003 CREATE  PROCEDURE `FindHotel`(
			  IN apply_markup int,
			  IN prm_region_id int,
			  IN prm_city_id int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_category_id int,
			  IN prm_tourismtype_id int,
			  IN prm_adults int,
			  IN prm_childs int,
			  IN prm_total_dias int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN

			  
			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.stars,
				  p.latitude,
				  p.longitude,
				  p.featured,
				  p.zone_id,
				  p.category_id,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  MIN(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_hotels_info p
				JOIN #__cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_hotels_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0) 
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				GROUP BY p.product_id
				having count(distinct(pre.date)) = prm_total_dias
				ORDER BY p.ordering;

			  ELSE



				SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.stars,
				  p.latitude,
				  p.longitude,
				  p.zone_id,
				  p.featured,
				  p.category_id,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  0 as markup
				FROM  #__cp_hotels_info p
				JOIN #__cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_hotels_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				GROUP BY p.product_id
				having count(distinct(pre.date)) = prm_total_dias
				ORDER BY p.ordering;

			  END IF;

			END */";$db->setQuery($query28);$db->query($query28); $query29="

			
			/*!50003 CREATE  PROCEDURE `FindHotelCities`(
			  IN apply_markup int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_total_dias int,
			  IN prm_tourismtype_id int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN


			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_hotels_info p
				JOIN #__cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id)
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				GROUP BY ci.city_id
				having count(distinct(pre.date)) = prm_total_dias
				ORDER BY p.ordering;

			  ELSE



				SELECT

				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  0 as markup
				FROM  #__cp_hotels_info p
				JOIN #__cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id)
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				GROUP BY ci.city_id
				having count(distinct(pre.date)) = prm_total_dias
				ORDER BY p.ordering;

			  END IF;




			END */";$db->setQuery($query29);$db->query($query29); $query30="

			
			/*!50003 CREATE  PROCEDURE `FindPlan`(
			  IN apply_markup int,
			  IN apply_tours int,
			  IN prm_region_id int,
			  IN prm_country_id int,
			  IN prm_city_id int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_category_id int,
			  IN prm_tourismtype_id int,
			  IN prm_adults int,
			  IN prm_childs int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN

			  
			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN

			   IF apply_tours=0 THEN

				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.duration as duration_text,
				  p.days_total as duration,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),      
				  IFNULL(mrk.value,pred_markup) as markup,
				  'plan' AS product_type
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id) 
				  AND if(prm_country_id=0,1,p.country_id=prm_country_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0) 
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  

				GROUP BY p.product_id
				ORDER BY p.ordering;

			  ELSE

				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.duration as duration_text,
				  p.days_total as duration,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),      
				  IFNULL(mrk.value,pred_markup) as markup,
				  p.ordering as ordering_val,
				  'plan' AS product_type
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id) 
				  AND if(prm_country_id=0,1,p.country_id=prm_country_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0) 
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  

				GROUP BY p.product_id
				  
				UNION ALL

				 SELECT
				  t.product_id,
				  t.product_name,
				  t.product_desc,      
				  t.latitude,
				  t.longitude,
				  t.category_id,
				  t.featured,
				  t.product_url,
				  t.product_code,
				  t.average_rating,
				  t.duration as duration_text,
				  t.days_total as duration,
				  min(tf.ordering),
				  tf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(tt.tourismtype_id)) as tourismtype,
				  count(distinct(tre.date)),      
				  IFNULL(mrk.value,pred_markup) as markup,
				  t.ordering as ordering_val,
				  'tour' AS product_type
				FROM  #__cp_tours_info t
				JOIN #__cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN #__cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN #__cp_prm_country co ON co.country_id=t.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=t.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_tours_files  ORDER BY ordering
				) tf on tf.product_id=t.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=t.product_id AND mrk.product_typeid='tours' AND idagency_group=prm_group_id
				WHERE t.published=1      
				  AND t.publish_up <  now()
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())
				  AND if(prm_region_id=0,1,t.region_id=prm_region_id) 
				  AND if(prm_country_id=0,1,t.country_id=prm_country_id)   
				  AND if(prm_city_id=0,1,t.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,t.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,tt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,tre.child_price>0) 
				  AND if(mrk.enabled=0,mrk.externalid=t.product_id AND mrk.product_typeid='plans' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,t.featured=prm_related)
				  AND t.access<=prm_access
				  AND tre.date between prm_checkin_date AND prm_checkout_date
				
				GROUP BY t.product_id

				ORDER BY ordering_val;
			   
			   END IF;

			  ELSE

				IF apply_tours=0 THEN

				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.duration as duration_text,
				  p.days_total as duration,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  'plan' AS product_type      
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id) 
				  AND if(prm_country_id=0,1,p.country_id=prm_country_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date
				  
				GROUP BY p.product_id
				ORDER BY p.ordering;

			   ELSE

				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.duration as duration_text,
				  p.days_total as duration,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  p.ordering as ordering_val,
				  'plan' AS product_type      
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)   
				  AND if(prm_country_id=0,1,p.country_id=prm_country_id)
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,p.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,pre.child_price>0)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date between prm_checkin_date AND prm_checkout_date

				GROUP BY p.product_id
				
				UNION ALL

				 SELECT
				  t.product_id,
				  t.product_name,
				  t.product_desc,      
				  t.latitude,
				  t.longitude,
				  t.category_id,
				  t.featured,
				  t.product_url,
				  t.product_code,
				  t.average_rating,
				  t.duration as duration_text,
				  t.days_total as duration,
				  min(tf.ordering),
				  tf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(tt.tourismtype_id)) as tourismtype,
				  count(distinct(tre.date)),
				  t.ordering as ordering_val,
				  'tour' AS product_type
				FROM  #__cp_tours_info t
				JOIN #__cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN #__cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN #__cp_prm_country co ON co.country_id=t.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=t.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_tours_files  ORDER BY ordering
				) tf on tf.product_id=t.product_id
				WHERE t.published=1      
				  AND t.publish_up <  now()
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())
				  AND if(prm_region_id=0,1,t.region_id=prm_region_id)  
				  AND if(prm_country_id=0,1,t.country_id=prm_country_id) 
				  AND if(prm_city_id=0,1,t.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,t.category_id=prm_category_id) 
				  AND if(prm_tourismtype_id=0,1,tt.tourismtype_id=prm_tourismtype_id) 
				  AND if(prm_childs=0,1,tre.child_price>0)
				  AND if(prm_related>1,1,t.featured=prm_related)
				  AND t.access<=prm_access
				  AND tre.date between prm_checkin_date AND prm_checkout_date

				  GROUP BY t.product_id

			   ORDER BY ordering_val;

			   END IF;
			  END IF;


			END */";$db->setQuery($query30);$db->query($query30); $query31="

			
			/*!50003 CREATE  PROCEDURE `FindPlanCities`(
			  IN apply_markup int,
			  IN prm_checkin_date date,
			  IN prm_tourismtype_id int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int

			)
			BEGIN


			  DECLARE pred_markup double DEFAULT 0;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;
				 SELECT
				  product_id,
				  country_id,
				  country_name,
				  city_id,
				  city_name,
				  currency_id,
				  min(basic_price) AS basic_price,
				  min(previous_value) as previous_value,
				  markup,
				  ordering_val

				  FROM
				  
				  (SELECT
				  p.product_id,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  IFNULL(mrk.value,pred_markup) as markup,
				  p.ordering as ordering_val
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id)
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date = prm_checkin_date
				  
				  GROUP BY ci.city_id
			   
				
				UNION ALL

				 SELECT
				  t.product_id,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.previous_price) as previous_value,
				  IFNULL(mrk.value,pred_markup) as markup,
				  t.ordering as ordering_val
				FROM  #__cp_tours_info t
				JOIN #__cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN #__cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN #__cp_prm_country co ON co.country_id=t.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=t.city_id
				LEFT JOIN #__markup mrk ON mrk.externalid=t.product_id AND mrk.product_typeid='tours' AND idagency_group=prm_group_id
				WHERE t.published=1      
				  AND t.publish_up <  now()
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,tt.tourismtype_id=prm_tourismtype_id)
				  AND if(mrk.enabled=0,mrk.externalid=t.product_id AND mrk.product_typeid='tours' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,t.featured=prm_related)
				  AND t.access<=prm_access
				  AND tre.date = prm_checkin_date
				 
				  GROUP BY ci.city_id)
			   
				  Derived GROUP BY city_id;

			  ELSE


				 SELECT
				  country_id,
				  country_name,
				  city_id,
				  city_name,
				  currency_id,
				  min(basic_price) AS basic_price,
				  min(previous_value) as previous_value,
				  markup

				  FROM
				  
				  (SELECT

				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  0 as markup
				FROM  #__cp_plans_info p
				JOIN #__cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN #__cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(prm_tourismtype_id=0,1,pt.tourismtype_id=prm_tourismtype_id)
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND pre.date = prm_checkin_date
				GROUP BY ci.city_id

				UNION ALL

				SELECT

				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.trevious_price) as trevious_value,
				  0 as markup
				FROM  #__cp_tours_info t
				JOIN #__cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN #__cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN #__cp_prm_country co ON co.country_id=t.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=t.city_id
				WHERE t.published=1      
				  AND t.publish_up <  now()
				  AND if(prm_tourismtype_id=0,1,tt.tourismtype_id=prm_tourismtype_id)
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())
				  AND if(prm_related>1,1,t.featured=prm_related)
				  AND t.access<=prm_access
				  AND tre.date = prm_checkin_date
				GROUP BY ci.city_id)
			   
				  Derived GROUP BY city_id;

			  END IF;



			END */";$db->setQuery($query31);$db->query($query31); $query32="

			
			/*!50003 CREATE  PROCEDURE `FindTransfer`(
			  IN apply_markup int,
			  IN prm_region_id int,
			  IN prm_city_id int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_category_id int,
			  IN prm_type int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN



			  DECLARE pred_markup double DEFAULT 0;
			  DECLARE total_date double DEFAULT 1;

			  IF(prm_type=2)THEN
				SET total_date = 2;
			  END IF;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.latitude,
				  p.longitude,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.featured,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  GROUP_CONCAT(distinct(tcc.category_id)) as category_id,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_transfers_info p
				JOIN #__cp_transfers_resume pre ON p.product_id=pre.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_transfers_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='transfers' AND idagency_group=prm_group_id
				LEFT JOIN #__cp_transfers_category tcc ON tcc.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)
				  AND if(prm_category_id=0,1,tcc.category_id=prm_category_id)
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='transfers' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND if(prm_type=3,pre.date = prm_checkin_date,if(prm_type=1,pre.date = prm_checkin_date,pre.date = prm_checkin_date OR pre.date = prm_checkout_date))


				GROUP BY p.product_id
				having count(distinct(pre.date)) = total_date
				ORDER BY p.ordering;

			  ELSE



				SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.latitude,
				  p.longitude,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.featured, 
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  GROUP_CONCAT(distinct(tcc.category_id)) as category_id,
				  0 as markup
				FROM  #__cp_transfers_info p
				JOIN #__cp_transfers_resume pre ON p.product_id=pre.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM #__cp_transfers_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN #__cp_transfers_category tcc ON tcc.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_region_id=0,1,p.region_id=prm_region_id)   
				  AND if(prm_city_id=0,1,p.city_id=prm_city_id)         
				  AND if(prm_category_id=0,1,tcc.category_id=prm_category_id)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND if(prm_type=3,pre.date = prm_checkin_date,if(prm_type=1,pre.date = prm_checkin_date,pre.date = prm_checkin_date OR pre.date = prm_checkout_date))
				  

				GROUP BY p.product_id
				having count(distinct(pre.date)) = total_date
				ORDER BY p.ordering;

			  END IF;


			END */";$db->setQuery($query32);$db->query($query32); $query33="


			/*!50003 CREATE  PROCEDURE `FindTransferCities`(
			  IN apply_markup int,
			  IN prm_checkin_date date,
			  IN prm_checkout_date date,
			  IN prm_type int,
			  IN prm_group_id int,
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_total_items int
			)
			BEGIN

			  DECLARE pred_markup double DEFAULT 0;
			  DECLARE total_date double DEFAULT 1;

			  IF(prm_type=2)THEN
				SET total_date = 2;
			  END IF;

			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN



				SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM  #__cp_transfers_info p
				JOIN #__cp_transfers_resume pre ON p.product_id=pre.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__cp_transfers_files pf ON pf.product_id=p.product_id
				LEFT JOIN #__markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='transfers' AND idagency_group=prm_group_id
				WHERE p.published=1
				  AND co.published=1
				  AND ci.published=1
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='transfers' AND mrk.enabled=1,1)
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND if(prm_type=3,pre.date = prm_checkin_date,if(prm_type=1,pre.date = prm_checkin_date,pre.date = prm_checkin_date OR pre.date = prm_checkout_date))


				GROUP BY ci.city_id
				having count(distinct(pre.date)) = total_date
				ORDER BY p.ordering;

			  ELSE



				SELECT
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  0 as markup
				FROM  #__cp_transfers_info p
				JOIN #__cp_transfers_resume pre ON p.product_id=pre.product_id
				JOIN #__cp_prm_country co ON co.country_id=p.country_id
				JOIN #__cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN #__cp_transfers_files pf ON pf.product_id=p.product_id
				WHERE p.published=1
				  AND co.published=1
				  AND ci.published=1
				  AND p.publish_up <  now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_related>1,1,p.featured=prm_related)
				  AND p.access<=prm_access
				  AND if(prm_type=3,pre.date = prm_checkin_date,if(prm_type=1,pre.date = prm_checkin_date,pre.date = prm_checkin_date OR pre.date = prm_checkout_date))


				GROUP BY ci.city_id
				having count(distinct(pre.date)) = total_date
				ORDER BY p.ordering;

			  END IF;

			END */";$db->setQuery($query33);$db->query($query33); $query34="


			/*!50003 CREATE  PROCEDURE `GenerateCarsRateDayResume`(rate_product_id INT, new_date DATE, calc_prices BOOLEAN)
			BEGIN 
			DECLARE daynumber INT;
			DECLARE product_stock INT DEFAULT 0;
			DECLARE rate_currency_id INT;
			DECLARE has_resume BOOLEAN;
			SET @season_count = 0;
			SET @selected_rate_id = 0;
			SET @basic_price_adult = 0;
			SET @previous_price_adult = 0;
			SET @prefix = '".$prefix."';
			SELECT WEEKDAY(new_date) + 1 INTO daynumber;
			SELECT currency_id INTO rate_currency_id FROM #__cp_prm_currency WHERE default_currency = 1;

			SET @season_sql = CONCAT('SELECT COUNT(r.season_id) INTO @season_count FROM ',@prefix,'cp_cars_rate 
			r INNER JOIN ',@prefix,'cp_cars_rate_price p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s 
			ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
			WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 
			AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date');
			PREPARE count_seasons FROM @season_sql;
			EXECUTE count_seasons;
			DEALLOCATE PREPARE count_seasons;


			IF @season_count > 0 THEN
				IF calc_prices THEN
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, p.price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_cars_rate r INNER JOIN ',@prefix,'cp_cars_rate_price 
					p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s ON r.season_id = s.season_id INNER 
					JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id WHERE r.product_id = ', rate_product_id, ' 
					AND p.price > 0 AND s.day', daynumber, ' != 0 AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') 
					BETWEEN d.start_date AND d.end_date ORDER BY s.is_special DESC, p.price ASC LIMIT 1');
				ELSE
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, r.basic_price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_cars_rate r INNER JOIN ',@prefix,'cp_prm_season s 
					ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
					WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 AND 
					STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date ORDER BY s.is_special 
					DESC, r.basic_price ASC LIMIT 1');
				END IF;
				PREPARE get_season FROM @season_sql;
				EXECUTE get_season;
				DEALLOCATE PREPARE get_season;

				IF @selected_rate_id > 0 THEN
					IF ISNULL(@previous_price_adult) < 0 THEN
						SET @previous_price_adult = 0;
					END IF;

					
					SELECT EXISTS (SELECT product_id FROM #__cp_cars_resume WHERE 
					product_id = rate_product_id AND `date` = new_date) INTO has_resume;
					IF has_resume THEN
						UPDATE #__cp_cars_resume SET adult_price = @basic_price_adult, 
							stock = product_stock, previous_price = @previous_price_adult 
							WHERE product_id = rate_product_id AND `date` = new_date;
					ELSE 
						INSERT INTO #__cp_cars_resume (product_id, `date`, stock, adult_price, currency_id, 
							previous_price) VALUES (rate_product_id, new_date, product_stock, 
							@basic_price_adult, rate_currency_id, @previous_price_adult);
					END IF;

				END IF;
			ELSE
				DELETE FROM #__cp_cars_resume WHERE product_id = rate_product_id AND `date` = new_date;
			END IF;
			END */";$db->setQuery($query34);$db->query($query34); $query35="


			/*!50003 CREATE  PROCEDURE `GenerateCarsRateResume`(start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE rate_product_id INT;
			DECLARE rate_date DATE;
			DECLARE log_last_date DATE;
			DECLARE record_not_found INT DEFAULT 0;
			DECLARE cursor_products CURSOR FOR SELECT product_id FROM #__cp_cars_info;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;


			DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

			SET rate_date = start_date;

			START TRANSACTION;

			OPEN cursor_products;

			all_products:LOOP

				FETCH cursor_products INTO rate_product_id;
				IF record_not_found THEN
					LEAVE all_products;
				END IF;

				
				WHILE rate_date <= end_date DO
					CALL GenerateCarsRateDayResume(rate_product_id, rate_date, calc_prices);
					SET rate_date = ADDDATE(rate_date, 1);
				END WHILE;
				SET rate_date = start_date;
			END LOOP all_products;
			CLOSE cursor_products;


			SELECT last_date INTO log_last_date FROM #__cp_rate_resume_log WHERE LOWER(product_type_code) = 'cars';
			IF log_last_date THEN
				IF log_last_date < end_date THEN
					UPDATE #__cp_rate_resume_log SET last_date = end_date WHERE LOWER(product_type_code) = 'cars';
				END IF;
			ELSE
				INSERT INTO #__cp_rate_resume_log (product_type_code, last_date) VALUES ('cars', end_date);
			END IF;

			COMMIT;

			END */";$db->setQuery($query35);$db->query($query35); $query36="


			/*!50003 CREATE  PROCEDURE `GenerateCarsRateResumeById`(product_id INT, start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE x DATE;


			SET x = start_date;


			WHILE x <= end_date DO
				CALL GenerateCarsRateDayResume(product_id, x, calc_prices);
				SET x = ADDDATE(x, 1);
			END WHILE;
			END */";
			
			$db->setQuery($query36);$db->query($query36); $query37="


			/*!50003 CREATE  PROCEDURE `GenerateHotelsRateDayResume`(rate_product_id INT, new_date DATE, calc_prices BOOLEAN)
			BEGIN 
			DECLARE daynumber INT;
			DECLARE product_stock INT DEFAULT 0;
			DECLARE rate_currency_id INT;
			DECLARE has_resume BOOLEAN;
			DECLARE basic_price_child DOUBLE DEFAULT 0;
			SET @season_count = 0;
			SET @selected_rate_id = 0;
			SET @basic_price_adult = 0;
			SET @previous_price_adult = 0;
			SET @prefix = '".$prefix."';
			SELECT WEEKDAY(new_date) + 1 INTO daynumber;
			SELECT currency_id INTO rate_currency_id FROM #__cp_prm_currency WHERE default_currency = 1;


			SET @season_sql = CONCAT('SELECT COUNT(r.season_id) INTO @season_count FROM ',@prefix,'cp_hotels_rate 
			r INNER JOIN ',@prefix,'cp_hotels_rate_price p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s 
			ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
			WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 
			AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date');
			PREPARE count_seasons FROM @season_sql;
			EXECUTE count_seasons;
			DEALLOCATE PREPARE count_seasons;


			IF @season_count > 0 THEN
				IF calc_prices THEN
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, p.price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_hotels_rate r INNER JOIN ',@prefix,'cp_hotels_rate_price 
					p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s ON r.season_id = s.season_id INNER 
					JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id WHERE r.product_id = ', rate_product_id, ' 
					AND p.is_child = 0 AND p.price > 0 AND s.day', daynumber, ' != 0 AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') 
					BETWEEN d.start_date AND d.end_date ORDER BY s.is_special DESC, p.price ASC LIMIT 1');
				ELSE
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, r.basic_price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_hotels_rate r INNER JOIN ',@prefix,'cp_prm_season s 
					ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
					WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 AND 
					STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date ORDER BY s.is_special 
					DESC, r.basic_price ASC LIMIT 1');
				END IF;
				PREPARE get_season FROM @season_sql;
				EXECUTE get_season;
				DEALLOCATE PREPARE get_season;

				IF @selected_rate_id > 0 THEN
					IF ISNULL(@previous_price_adult) < 0 THEN
						SET @previous_price_adult = 0;
					END IF;

					
					SELECT MIN(price) INTO basic_price_child FROM #__cp_hotels_rate_price 
					WHERE rate_id = @selected_rate_id AND is_child = 1;
					IF ISNULL(basic_price_child) THEN
						SET basic_price_child = 0;
					END IF;

					
					SELECT EXISTS (SELECT product_id FROM #__cp_hotels_resume WHERE 
					product_id = rate_product_id AND `date` = new_date) INTO has_resume;
					IF has_resume THEN
						UPDATE #__cp_hotels_resume SET adult_price = @basic_price_adult, child_price = 
							basic_price_child, stock = product_stock, previous_price = @previous_price_adult 
							WHERE product_id = rate_product_id AND `date` = new_date;
					ELSE 
						INSERT INTO #__cp_hotels_resume (product_id, `date`, stock, adult_price, currency_id, 
							child_price, previous_price) VALUES (rate_product_id, new_date, product_stock, 
							@basic_price_adult, rate_currency_id, basic_price_child, @previous_price_adult);
					END IF;

				END IF;
			ELSE
				DELETE FROM #__cp_hotels_resume WHERE product_id = rate_product_id AND `date` = new_date;
			END IF;
			END */";$db->setQuery($query37);$db->query($query37); $query38="


			/*!50003 CREATE  PROCEDURE `GenerateHotelsRateResume`(start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE rate_product_id INT;
			DECLARE rate_date DATE;
			DECLARE log_last_date DATE;
			DECLARE record_not_found INT DEFAULT 0;
			DECLARE cursor_products CURSOR FOR SELECT product_id FROM #__cp_hotels_info;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;


			DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

			SET rate_date = start_date;

			START TRANSACTION;

			OPEN cursor_products;

			all_products:LOOP

				FETCH cursor_products INTO rate_product_id;
				IF record_not_found THEN
					LEAVE all_products;
				END IF;

				
				WHILE rate_date <= end_date DO
					CALL GenerateHotelsRateDayResume(rate_product_id, rate_date, calc_prices);
					SET rate_date = ADDDATE(rate_date, 1);
				END WHILE;
				SET rate_date = start_date;
			END LOOP all_products;
			CLOSE cursor_products;


			SELECT last_date INTO log_last_date FROM #__cp_rate_resume_log WHERE LOWER(product_type_code) = 'hotels';
			IF log_last_date THEN
				IF log_last_date < end_date THEN
					UPDATE #__cp_rate_resume_log SET last_date = end_date WHERE LOWER(product_type_code) = 'hotels';
				END IF;
			ELSE
				INSERT INTO #__cp_rate_resume_log (product_type_code, last_date) VALUES ('hotels', end_date);
			END IF;

			COMMIT;

			END */";$db->setQuery($query38);$db->query($query38); $query39="


			/*!50003 CREATE  PROCEDURE `GenerateHotelsRateResumeById`(product_id INT, start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE x DATE;


			SET x = start_date;


			WHILE x <= end_date DO
				CALL GenerateHotelsRateDayResume(product_id, x, calc_prices);
				SET x = ADDDATE(x, 1);
			END WHILE;
			END */";$db->setQuery($query39);$db->query($query39); $query40="


			/*!50003 CREATE  PROCEDURE `GeneratePlansRateDayResume`(rate_product_id INT, new_date DATE, calc_prices BOOLEAN)
			BEGIN 
			DECLARE daynumber INT;
			DECLARE product_stock INT DEFAULT 0;
			DECLARE rate_currency_id INT;
			DECLARE has_resume BOOLEAN;
			DECLARE basic_price_child DOUBLE DEFAULT 0;
			SET @season_count = 0;
			SET @selected_rate_id = 0;
			SET @basic_price_adult = 0;
			SET @previous_price_adult = 0;
			SET @prefix = '".$prefix."';

			SELECT WEEKDAY(new_date) + 1 INTO daynumber;
			SELECT currency_id INTO rate_currency_id FROM #__cp_prm_currency WHERE default_currency = 1;

			SET @season_sql = CONCAT('SELECT COUNT(r.season_id) INTO @season_count FROM ',@prefix,'cp_plans_rate 
			r INNER JOIN ',@prefix,'cp_plans_rate_price p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s 
			ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
			WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 
			AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date');
			PREPARE count_seasons FROM @season_sql;
			EXECUTE count_seasons;
			DEALLOCATE PREPARE count_seasons;


			IF @season_count > 0 THEN
				IF calc_prices THEN
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, p.price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_plans_rate r INNER JOIN ',@prefix,'cp_plans_rate_price 
					p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s ON r.season_id = s.season_id INNER 
					JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id WHERE r.product_id = ', rate_product_id, ' 
					AND p.is_child = 0 AND p.price > 0 AND s.day', daynumber, ' != 0 AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') 
					BETWEEN d.start_date AND d.end_date ORDER BY s.is_special DESC, p.price ASC LIMIT 1');
				ELSE
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, r.basic_price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_plans_rate r INNER JOIN ',@prefix,'cp_prm_season s 
					ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
					WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 AND 
					STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date ORDER BY s.is_special 
					DESC, r.basic_price ASC LIMIT 1');
				END IF;
				PREPARE get_season FROM @season_sql;
				EXECUTE get_season;
				DEALLOCATE PREPARE get_season;

				IF @selected_rate_id > 0 THEN
					IF ISNULL(@previous_price_adult) < 0 THEN
						SET @previous_price_adult = 0;
					END IF;

					
					SELECT MIN(price) INTO basic_price_child FROM #__cp_plans_rate_price 
					WHERE rate_id = @selected_rate_id AND is_child = 1;
					IF ISNULL(basic_price_child) THEN
						SET basic_price_child = 0;
					END IF;

					SELECT EXISTS (SELECT product_id FROM #__cp_plans_resume WHERE 
					product_id = rate_product_id AND `date` = new_date) INTO has_resume;
					IF has_resume THEN
						UPDATE #__cp_plans_resume SET adult_price = @basic_price_adult, child_price = 
							basic_price_child, stock = product_stock, previous_price = @previous_price_adult 
							WHERE product_id = rate_product_id AND `date` = new_date;
					ELSE 

						INSERT INTO #__cp_plans_resume (product_id, `date`, stock, adult_price, currency_id, 
							child_price, previous_price) VALUES (rate_product_id, new_date, product_stock, 
							@basic_price_adult, rate_currency_id, basic_price_child, @previous_price_adult);
					END IF;

				END IF;
			ELSE
				DELETE FROM #__cp_plans_resume WHERE product_id = rate_product_id AND `date` = new_date;
			END IF;
			END */";$db->setQuery($query40);$db->query($query40); $query41="


			/*!50003 CREATE  PROCEDURE `GeneratePlansRateResume`(start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE rate_product_id INT;
			DECLARE rate_date DATE;
			DECLARE log_last_date DATE;
			DECLARE record_not_found INT DEFAULT 0;
			DECLARE cursor_products CURSOR FOR SELECT product_id FROM #__cp_plans_info;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;


			DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

			SET rate_date = start_date;

			START TRANSACTION;

			OPEN cursor_products;

			all_products:LOOP

				FETCH cursor_products INTO rate_product_id;
				IF record_not_found THEN
					LEAVE all_products;
				END IF;

				
				WHILE rate_date <= end_date DO
					CALL GeneratePlansRateDayResume(rate_product_id, rate_date, calc_prices);
					SET rate_date = ADDDATE(rate_date, 1);
				END WHILE;
				SET rate_date = start_date;
			END LOOP all_products;
			CLOSE cursor_products;


			SELECT last_date INTO log_last_date FROM #__cp_rate_resume_log WHERE LOWER(product_type_code) = 'plans';
			IF log_last_date THEN
				IF log_last_date < end_date THEN
					UPDATE #__cp_rate_resume_log SET last_date = end_date WHERE LOWER(product_type_code) = 'plans';
				END IF;
			ELSE
				INSERT INTO #__cp_rate_resume_log (product_type_code, last_date) VALUES ('plans', end_date);
			END IF;

			COMMIT;

			END */";$db->setQuery($query41);$db->query($query41); $query42="


			/*!50003 CREATE  PROCEDURE `GeneratePlansRateResumeById`(product_id INT, start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE x DATE;


			SET x = start_date;


			WHILE x <= end_date DO
				CALL GeneratePlansRateDayResume(product_id, x, calc_prices);
				SET x = ADDDATE(x, 1);
			END WHILE;
			END */";$db->setQuery($query42);$db->query($query42); $query43="

			
			/*!50003 CREATE  PROCEDURE `GenerateToursRateDayResume`(rate_product_id INT, new_date DATE, calc_prices BOOLEAN)
			BEGIN 
			DECLARE daynumber INT;
			DECLARE product_stock INT DEFAULT 0;
			DECLARE rate_currency_id INT;
			DECLARE has_resume BOOLEAN;
			DECLARE basic_price_child DOUBLE DEFAULT 0;
			SET @season_count = 0;
			SET @selected_rate_id = 0;
			SET @basic_price_adult = 0;
			SET @previous_price_adult = 0;
			SET @prefix = '".$prefix."';
			SELECT WEEKDAY(new_date) + 1 INTO daynumber;
			SELECT currency_id INTO rate_currency_id FROM #__cp_prm_currency WHERE default_currency = 1;

			SET @season_sql = CONCAT('SELECT COUNT(r.season_id) INTO @season_count FROM ',@prefix,'cp_tours_rate 
			r INNER JOIN ',@prefix,'cp_tours_rate_price p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s 
			ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
			WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 
			AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date');
			PREPARE count_seasons FROM @season_sql;
			EXECUTE count_seasons;
			DEALLOCATE PREPARE count_seasons;


			IF @season_count > 0 THEN
				IF calc_prices THEN
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, p.price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_tours_rate r INNER JOIN ',@prefix,'cp_tours_rate_price 
					p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s ON r.season_id = s.season_id INNER 
					JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id WHERE r.product_id = ', rate_product_id, ' 
					AND p.price > 0 AND s.day', daynumber, ' != 0 AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') 
					BETWEEN d.start_date AND d.end_date ORDER BY s.is_special DESC, p.price ASC LIMIT 1');
				ELSE
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, r.basic_price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_tours_rate r INNER JOIN ',@prefix,'cp_prm_season s 
					ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
					WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 AND 
					STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date ORDER BY s.is_special 
					DESC, r.basic_price ASC LIMIT 1');
				END IF;
				PREPARE get_season FROM @season_sql;
				EXECUTE get_season;
				DEALLOCATE PREPARE get_season;

				IF @selected_rate_id > 0 THEN
					IF ISNULL(@previous_price_adult) < 0 THEN
						SET @previous_price_adult = 0;
					END IF;

					SELECT MIN(price) INTO basic_price_child FROM #__cp_tours_rate_price 
					WHERE rate_id = @selected_rate_id AND param2 = 2;
					IF ISNULL(basic_price_child) THEN
						SET basic_price_child = 0;
					END IF;
					
					SELECT EXISTS (SELECT product_id FROM #__cp_tours_resume WHERE 
					product_id = rate_product_id AND `date` = new_date) INTO has_resume;
					IF has_resume THEN
						UPDATE #__cp_tours_resume SET adult_price = @basic_price_adult, child_price = 
							basic_price_child, stock = product_stock, previous_price = @previous_price_adult 
							WHERE product_id = rate_product_id AND `date` = new_date;
					ELSE 
						INSERT INTO #__cp_tours_resume (product_id, `date`, stock, adult_price, currency_id, 
							child_price, previous_price) VALUES (rate_product_id, new_date, product_stock, 
							@basic_price_adult, rate_currency_id, basic_price_child, @previous_price_adult);
					END IF;

				END IF;
			ELSE
				DELETE FROM #__cp_tours_resume WHERE product_id = rate_product_id AND `date` = new_date;
			END IF;
			END */";$db->setQuery($query43);$db->query($query43); $query44="


			/*!50003 CREATE  PROCEDURE `GenerateToursRateResume`(start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE rate_product_id INT;
			DECLARE rate_date DATE;
			DECLARE log_last_date DATE;
			DECLARE record_not_found INT DEFAULT 0;
			DECLARE cursor_products CURSOR FOR SELECT product_id FROM #__cp_tours_info;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;


			DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

			SET rate_date = start_date;

			START TRANSACTION;

			OPEN cursor_products;

			all_products:LOOP

				FETCH cursor_products INTO rate_product_id;
				IF record_not_found THEN
					LEAVE all_products;
				END IF;

				
				WHILE rate_date <= end_date DO
					CALL GenerateToursRateDayResume(rate_product_id, rate_date, calc_prices);
					SET rate_date = ADDDATE(rate_date, 1);
				END WHILE;
				SET rate_date = start_date;
			END LOOP all_products;
			CLOSE cursor_products;


			SELECT last_date INTO log_last_date FROM #__cp_rate_resume_log WHERE LOWER(product_type_code) = 'tours';
			IF log_last_date THEN
				IF log_last_date < end_date THEN
					UPDATE #__cp_rate_resume_log SET last_date = end_date WHERE LOWER(product_type_code) = 'tours';
				END IF;
			ELSE
				INSERT INTO #__cp_rate_resume_log (product_type_code, last_date) VALUES ('tours', end_date);
			END IF;

			COMMIT;

			END */";$db->setQuery($query44);$db->query($query44); $query46="


			/*!50003 CREATE  PROCEDURE `GenerateToursRateResumeById`(product_id INT, start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE x DATE;


			SET x = start_date;


			WHILE x <= end_date DO
				CALL GenerateToursRateDayResume(product_id, x, calc_prices);
				SET x = ADDDATE(x, 1);
			END WHILE;
			END */";$db->setQuery($query46);$db->query($query46); $query47="


			/*!50003 CREATE  PROCEDURE `GenerateTransfersRateDayResume`(rate_product_id INT, new_date DATE, calc_prices BOOLEAN)
			BEGIN 
			DECLARE daynumber INT;
			DECLARE product_stock INT DEFAULT 0;
			DECLARE rate_currency_id INT;
			DECLARE has_resume BOOLEAN;
			SET @season_count = 0;
			SET @selected_rate_id = 0;
			SET @basic_price_adult = 0;
			SET @previous_price_adult = 0;
			SET @prefix = '".$prefix."';

			SELECT WEEKDAY(new_date) + 1 INTO daynumber;
			SELECT currency_id INTO rate_currency_id FROM #__cp_prm_currency WHERE default_currency = 1;

			SET @season_sql = CONCAT('SELECT COUNT(r.season_id) INTO @season_count FROM ',@prefix,'cp_transfers_rate 
			r INNER JOIN ',@prefix,'cp_transfers_rate_price p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s 
			ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
			WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 
			AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date');
			PREPARE count_seasons FROM @season_sql;
			EXECUTE count_seasons;
			DEALLOCATE PREPARE count_seasons;


			IF @season_count > 0 THEN
				IF calc_prices THEN
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, p.price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_transfers_rate r INNER JOIN ',@prefix,'cp_transfers_rate_price 
					p ON r.rate_id = p.rate_id INNER JOIN ',@prefix,'cp_prm_season s ON r.season_id = s.season_id INNER 
					JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id WHERE r.product_id = ', rate_product_id, ' 
					AND p.price > 0 AND s.day', daynumber, ' != 0 AND STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') 
					BETWEEN d.start_date AND d.end_date ORDER BY s.is_special DESC, p.price ASC LIMIT 1');
				ELSE
					SET @season_sql := CONCAT('SELECT r.rate_id, r.previous_value, r.basic_price INTO @selected_rate_id, 
					@previous_price_adult, @basic_price_adult FROM ',@prefix,'cp_transfers_rate r INNER JOIN ',@prefix,'cp_prm_season s 
					ON r.season_id = s.season_id INNER JOIN ',@prefix,'cp_prm_season_date d ON s.season_id = d.season_id 
					WHERE r.product_id = ', rate_product_id, ' AND r.basic_price > 0 AND s.day', daynumber, ' != 0 AND 
					STR_TO_DATE(\'', new_date, '\', \'%Y-%m-%d\') BETWEEN d.start_date AND d.end_date ORDER BY s.is_special 
					DESC, r.basic_price ASC LIMIT 1');
				END IF;
				PREPARE get_season FROM @season_sql;
				EXECUTE get_season;
				DEALLOCATE PREPARE get_season;

				IF @selected_rate_id > 0 THEN
					IF ISNULL(@previous_price_adult) < 0 THEN
						SET @previous_price_adult = 0;
					END IF;

					
					SELECT EXISTS (SELECT product_id FROM #__cp_transfers_resume WHERE 
					product_id = rate_product_id AND `date` = new_date) INTO has_resume;
					IF has_resume THEN
						UPDATE #__cp_transfers_resume SET adult_price = @basic_price_adult, 
							stock = product_stock, previous_price = @previous_price_adult 
							WHERE product_id = rate_product_id AND `date` = new_date;
					ELSE 
						INSERT INTO #__cp_transfers_resume (product_id, `date`, stock, adult_price, currency_id, 
							previous_price) VALUES (rate_product_id, new_date, product_stock, 
							@basic_price_adult, rate_currency_id, @previous_price_adult);
					END IF;

				END IF;
			ELSE
				DELETE FROM #__cp_transfers_resume WHERE product_id = rate_product_id AND `date` = new_date;
			END IF;
			END */";$db->setQuery($query47);$db->query($query47); $query48="


			/*!50003 CREATE  PROCEDURE `GenerateTransfersRateResume`(start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE rate_product_id INT;
			DECLARE rate_date DATE;
			DECLARE log_last_date DATE;
			DECLARE record_not_found INT DEFAULT 0;
			DECLARE cursor_products CURSOR FOR SELECT product_id FROM #__cp_transfers_info;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET record_not_found = 1;


			DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

			SET rate_date = start_date;

			START TRANSACTION;

			OPEN cursor_products;

			all_products:LOOP

				FETCH cursor_products INTO rate_product_id;
				IF record_not_found THEN
					LEAVE all_products;
				END IF;

				
				WHILE rate_date <= end_date DO
					CALL GenerateTransfersRateDayResume(rate_product_id, rate_date, calc_prices);
					SET rate_date = ADDDATE(rate_date, 1);
				END WHILE;
				SET rate_date = start_date;
			END LOOP all_products;
			CLOSE cursor_products;


			SELECT last_date INTO log_last_date FROM #__cp_rate_resume_log WHERE LOWER(product_type_code) = 'transfers';
			IF log_last_date THEN
				IF log_last_date < end_date THEN
					UPDATE #__cp_rate_resume_log SET last_date = end_date WHERE LOWER(product_type_code) = 'transfers';
				END IF;
			ELSE
				INSERT INTO #__cp_rate_resume_log (product_type_code, last_date) VALUES ('transfers', end_date);
			END IF;

			COMMIT;

			END */";$db->setQuery($query48);$db->query($query48); $query49="


			/*!50003 CREATE  PROCEDURE `GenerateTransfersRateResumeById`(product_id INT, start_date DATE, end_date DATE, calc_prices BOOLEAN)
			BEGIN
			DECLARE x DATE;

			SET x = start_date;

			WHILE x <= end_date DO
				CALL GenerateTransfersRateDayResume(product_id, x, calc_prices);
				SET x = ADDDATE(x, 1);
			END WHILE;
			END */";$db->setQuery($query49);$db->query($query49); $query50="

			
			/*!50003 CREATE  PROCEDURE `GetAllProducts`(
			  IN filter_product_type varchar(20),
			  IN filter_status int,
			  IN filter_view_all int,
			  IN filter_text varchar(20),
			  IN filter_order_by varchar(20),
			  IN param_group_id int,
			  IN pred_markup double,
			  IN param_limit_start int,
			  IN param_limit int
			)
			BEGIN


			  SET @total_items = 0;
			  SET @prefix = '".$prefix."';

			  
			  IF filter_product_type = 'hotels' THEN

				set @product_count := CONCAT('
				  SELECT
					count(*) INTO @total_items
				  FROM ',@prefix,'cp_hotels_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\'hotels\' AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;

				set @product_sql := CONCAT('
				  SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\'hotels\' as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_hotels_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"hotels\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,''
				);
				

				PREPARE get_products FROM @product_sql;



			  
			  ELSEIF filter_product_type = 'plans' THEN

				set @product_count := CONCAT('
				  SELECT
					count(*) INTO @total_items
				  FROM ',@prefix,'cp_plans_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"plans\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;


				set @product_sql := CONCAT('
				  SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"plans\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_plans_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"plans\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,''
				);
				

				PREPARE get_products FROM @product_sql;

			ELSEIF filter_product_type = 'tours' THEN

				set @product_count := CONCAT('
				  SELECT
					count(*) INTO @total_items
				  FROM ',@prefix,'cp_tours_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"tours\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;


				set @product_sql := CONCAT('
				  SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"tours\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_tours_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"tours\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,''
				);
				

				PREPARE get_products FROM @product_sql;


			  
			  ELSEIF filter_product_type = 'transfers' THEN

				set @product_count := CONCAT('
				  SELECT
					count(*) INTO @total_items
				  FROM ',@prefix,'cp_transfers_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"transfers\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;

				set @product_sql := CONCAT('
				  SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"transfers\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_transfers_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"transfers\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,''
				);
				

				PREPARE get_products FROM @product_sql;

			  
			  ELSEIF filter_product_type = 'cars' THEN



				set @product_count := CONCAT('
				  SELECT
					count(*) INTO @total_items
				  FROM ',@prefix,'cp_cars_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"cars\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;


				set @product_sql := CONCAT('
				  SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"cars\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_cars_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"cars\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,''
				);
				

				PREPARE get_products FROM @product_sql;

			  
			  ELSE

				
				set @product_count := CONCAT('
				SELECT
				(SELECT
					count(*) as total
				  FROM ',@prefix,'cp_hotels_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"hotels\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\")))
				+
				(SELECT
					count(*) as total
				  FROM ',@prefix,'cp_plans_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"plans\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\")))
				+
				(SELECT
					count(*) as total
				  FROM ',@prefix,'cp_tours_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"cars\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  ) INTO @total_items
				');

				PREPARE get_num_products FROM @product_count;
				EXECUTE get_num_products;
				DEALLOCATE PREPARE get_num_products;


				
				set @product_sql := CONCAT('
				(SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"hotels\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_hotels_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"hotels\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  )
				UNION
				(SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"plans\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_plans_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"plans\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  )     
				UNION
				(SELECT
					p.product_id,
					p.product_name,
					p.product_code,
					p.product_desc,
					IFNULL(m.value,',pred_markup,') as markup,
					IFNULL(m.enabled,1) as enabled,
					\"tours\" as product_type,
					@total_items as total_items
				  FROM ',@prefix,'cp_tours_info p
					LEFT JOIN ',@prefix,'markup m ON m.externalid=p.product_id AND m.product_typeid=\"tours\" AND m.idagency_group=',param_group_id,'
				  WHERE p.published=1
					AND if(',filter_status,'=0,1,if(',filter_status,'=1,if(m.enabled=0,m.externalid=p.product_id AND m.enabled=1,1),m.enabled<>1 and m.externalid=p.product_id))
					AND if(',filter_view_all,'=0,1,m.externalid=p.product_id)
					AND if(\"',filter_text,'\"=\"\",1,(p.product_name like \"',filter_text,'\" OR p.product_code like \"',filter_text,'\"))
				  )
				  ORDER BY ',filter_order_by,' asc
				  LIMIT ',param_limit_start,', ',param_limit,'
				');

				PREPARE get_products FROM @product_sql;

			  END IF;



			  EXECUTE get_products;

			END */";$db->setQuery($query50);$db->query($query50); $query51="

			/*!50003 CREATE  PROCEDURE `GetAmenity`(
			  IN lang	varchar(2),
			  IN param_product_id int
			)
			BEGIN

				SELECT
				  a.amenity_id,
				  IFNULL(jf.value,a.amenity_name) as amenity_name,
				  imageurl
				FROM  #__cp_prm_hotels_amenity a
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (a.amenity_id=jf.reference_id AND jf.reference_table = '#__cp_prm_hotels_amenity' AND jf.published=1)
				  JOIN #__cp_hotels_amenity ha
				  ON ha.amenity_id = a.amenity_id
				WHERE a.published=1
				  AND ha.product_id = param_product_id
				GROUP BY a.amenity_id;

			END */";$db->setQuery($query51);$db->query($query51); $query52="

			
			/*!50003 CREATE  PROCEDURE `GetAvaibleCities`(
			  IN	lang	varchar(2),
			  IN  regionId int,
			  IN  countryId int,
			  IN  product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  c.city_id,
				  IFNULL(jf.value,c.city_name) as city_name,
				  c.city_code,
				  c.country_id,
				  c.region_id
				FROM  #__cp_prm_city c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_city' AND jf.published=1)
				  JOIN #__cp_hotels_info h
				  ON h.city_id = c.city_id
				WHERE h.published=1
				  AND if(regionId=0,1,c.region_id=regionId)         
				  GROUP BY c.city_id
				  ORDER BY c.city_name;


			  
			  ELSEIF product = 'plan' THEN
				
				SELECT
				  c.city_id,
				  IFNULL(jf.value,c.city_name) as city_name,
				  c.city_code,
				  c.country_id,
				  c.region_id
				FROM  #__cp_prm_city c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_city' AND jf.published=1)
				  ,#__cp_plans_info p, #__cp_tours_info t
				  
				WHERE p.published=1
				  AND if(regionId=0,1,c.region_id=regionId)
				  AND if(countryId=0,1,c.country_id=countryId)      
				  AND (t.city_id = c.city_id OR p.city_id = c.city_id)
				GROUP BY c.city_id
				ORDER BY c.city_name;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  c.city_id,
				  IFNULL(jf.value,c.city_name) as city_name,
				  c.city_code,
				  c.country_id,
				  c.region_id
				FROM  #__cp_prm_city c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_city' AND jf.published=1)
				  JOIN #__cp_cars_info p
				  ON p.city_id = c.city_id
				WHERE p.published=1
				  AND if(regionId=0,1,c.region_id=regionId)         
				  GROUP BY c.city_id
				   ORDER BY c.city_name;

			   
			  ELSEIF product = 'transfer' THEN
				SELECT
				  c.city_id,
				  IFNULL(jf.value,c.city_name) as city_name,
				  c.city_code,
				  c.country_id,
				  c.region_id
				FROM  #__cp_prm_city c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_city' AND jf.published=1)
				  JOIN #__cp_transfers_info p
				  ON p.city_id = c.city_id
				WHERE p.published=1
				  AND if(regionId=0,1,c.region_id=regionId)         
				  GROUP BY c.city_id
				   ORDER BY c.city_name;


			  
			  ELSE

				SELECT
				  c.city_id,
				  IFNULL(jf.value,c.city_name) as city_name,
				  c.city_code,
				  c.country_id,
				  c.region_id
				FROM  #__cp_prm_city c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_city' AND jf.published=1)
				WHERE c.region_id=regionId
				  GROUP BY c.city_id
				   ORDER BY c.city_name;

			  END IF;

			END */";$db->setQuery($query52);$db->query($query52); $query53="

			
			/*!50003 CREATE  PROCEDURE `GetAvaibleCountries`(
			  IN	lang	varchar(2),
			  IN  product varchar(50)
			)
			BEGIN
				DECLARE sentencia varchar(500);

			  IF product = 'plan' THEN

				SELECT
				  c.country_id,
				  IFNULL(jf.value,c.country_name) as country_name,
				  c.country_code,
				  c.country_id
				FROM  #__cp_prm_country c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.country_id=jf.reference_id AND jf.reference_table = 'cp_prm_country' AND jf.published=1)
				  , #__cp_plans_info p,
				  #__cp_tours_info t
				WHERE p.published=1
				  AND (t.country_id = c.country_id OR p.country_id = c.country_id)
				 GROUP BY c.country_id
				order by c.country_name;

			  END IF;

			END */";$db->setQuery($query53);$db->query($query53); $query54="

			
			/*!50003 CREATE  PROCEDURE `GetAvaibleRegions`(
			  IN	lang	varchar(2),
			  IN  product varchar(50)
			)
			BEGIN

			  DECLARE sentencia varchar(500);

			  
			  IF product = 'hotel' THEN

				SELECT
				  r.region_id,
				  IFNULL(jf.value,r.region_name) as region_name,
				  r.region_code,
				  r.country_id
				FROM  #__cp_prm_region r
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (r.region_id=jf.reference_id AND jf.reference_table = 'cp_prm_region' AND jf.published=1)
				  JOIN #__cp_hotels_info h
				  ON h.region_id = r.region_id
				WHERE r.published=1
				  AND h.published=1
				GROUP BY r.region_id
			   order by r.region_name;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  r.region_id,
				  IFNULL(jf.value,r.region_name) as region_name,
				  r.region_code,
				  r.country_id
				FROM  #__cp_prm_region r
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (r.region_id=jf.reference_id AND jf.reference_table = 'cp_prm_region' AND jf.published=1)
				  , #__cp_plans_info p, #__cp_tours_info t
				  
				WHERE r.published=1
				  AND p.published=1
				  AND (t.region_id = r.region_id OR p.region_id = r.region_id)
				 GROUP BY r.region_id
				order by r.region_name;


			  
			  ELSEIF product = 'transfer' THEN
				SELECT
				  r.region_id,
				  IFNULL(jf.value,r.region_name) as region_name,
				  r.region_code,
				  r.country_id
				FROM  #__cp_prm_region r
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (r.region_id=jf.reference_id AND jf.reference_table = 'cp_prm_region' AND jf.published=1)
				  JOIN #__cp_transfers_info t
				  ON t.region_id = r.region_id
				WHERE r.published=1
				  AND t.published=1
				 GROUP BY r.region_id
				order by r.region_name;


			  
			  ELSEIF product = 'car' THEN
				SELECT
				  r.region_id,
				  IFNULL(jf.value,r.region_name) as region_name,
				  r.region_code,
				  r.country_id
				FROM  #__cp_prm_region r
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (r.region_id=jf.reference_id AND jf.reference_table = 'cp_prm_region' AND jf.published=1)
				  JOIN #__cp_cars_info c
				  ON c.region_id = r.region_id
				WHERE r.published=1
				  AND c.published=1
				 GROUP BY r.region_id
				order by r.region_name;


			  
			  ELSE

				SELECT
				  r.region_id,
				  IFNULL(jf.value,r.region_name) as region_name,
				  r.region_code,
				  r.country_id
				FROM #__cp_prm_region r
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (r.region_id=jf.reference_id AND jf.reference_table = 'cp_prm_region' AND jf.published=1)
				WHERE r.published=1
				 GROUP BY r.region_id
				order by r.region_name;

			  END IF;


			END */";$db->setQuery($query54);$db->query($query54); $query55="

			
			/*!50003 CREATE  PROCEDURE `GetCategory`(
			  IN	lang	varchar(2),
			  IN  product varchar(50),
			  IN param_transfet_type int
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name
				FROM  #__cp_prm_hotels_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_hotels_category' AND jf.published=1)
				  JOIN #__cp_hotels_info h
				  ON h.category_id = c.category_id
				WHERE c.published=1
				  AND h.published=1
				GROUP BY c.category_id
				ORDER BY category_name;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name
				FROM  #__cp_prm_plans_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_plans_category' AND jf.published=1)
				  JOIN #__cp_plans_info p
				  ON p.category_id = c.category_id
				WHERE c.published=1
				  AND p.published=1
				GROUP BY c.category_id
				ORDER BY category_name;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name
				FROM  #__cp_prm_cars_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_cars_category' AND jf.published=1)
				  JOIN #__cp_cars_info p
				  ON p.category_id = c.category_id
				WHERE c.published=1
				  AND p.published=1
				GROUP BY c.category_id
				ORDER BY category_name;


			  ELSEIF product = 'transfer' THEN


				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name
				FROM  #__cp_prm_transfers_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_transfers_category' AND jf.published=1)
				  JOIN #__cp_transfers_category tc ON tc.category_id=c.category_id
				  JOIN #__cp_transfers_info p ON p.product_id=tc.product_id
				WHERE c.published=1
				  AND p.published=1
				  AND IF(param_transfet_type!=0,c.transfer_type=param_transfet_type,1)
				GROUP BY c.category_id
				ORDER BY category_name;

			  ELSEIF product = 'tour' THEN
				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name
				FROM  #__cp_prm_tours_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_tours_category' AND jf.published=1)
				  JOIN #__cp_tours_info t
				  ON t.category_id = c.category_id
				WHERE c.published=1
				  AND t.published=1
				GROUP BY c.category_id
				ORDER BY category_name;

			  ELSE

				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.region_name) as category_name
				FROM  #__cp_prm_hotels_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = '#__cp_prm_hotels_category' AND jf.published=1)
				WHERE c.published=1
				GROUP BY c.category_id;

			  END IF;

			END */";$db->setQuery($query55);$db->query($query55); $query56="


			/*!50003 CREATE  PROCEDURE `GetCategoryTransfer`(
			  IN	lang	varchar(2),
			  IN  param_productId int
			)
			BEGIN


				SELECT
				  c.category_id,
				  IFNULL(jf.value,c.category_name) as category_name,
				  c.transfer_type
				FROM  #__cp_prm_transfers_category c
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (c.category_id=jf.reference_id AND jf.reference_table = 'cp_prm_transfers_category' AND jf.published=1)
				  JOIN #__cp_transfers_category tc ON tc.category_id=c.category_id
				  JOIN #__cp_transfers_info p ON p.product_id=tc.product_id
				WHERE c.published=1
				  AND p.published=1
				  AND tc.product_id=param_productId
				GROUP BY c.category_id
				ORDER BY category_name;

			END */";$db->setQuery($query56);$db->query($query56); $query57="


			/*!50003 CREATE  PROCEDURE `GetComment`(
			  IN param_product_id int,
			  IN product varchar(50)
			)
			BEGIN


			  
			  IF product = 'hotel' THEN

				SELECT *
				FROM #__cp_hotels_comments
				WHERE product_id =param_product_id
				 AND published=1;

			  ELSEIF product = 'plan' THEN
				SELECT *
				FROM #__cp_plans_comments
				WHERE product_id =param_product_id
				 AND published=1;


			  ELSEIF product = 'car' THEN
				SELECT *
				FROM #__cp_cars_comments
				WHERE product_id =param_product_id
				 AND published=1;


			  ELSEIF product = 'transfer' THEN
				SELECT *
				FROM #__cp_transfers_comments
				WHERE product_id =param_product_id
				 AND published=1;

			  ELSEIF product = 'tour' THEN
				SELECT *
				FROM #__cp_tours_comments
				WHERE product_id =param_product_id
				 AND published=1;

			  END IF;

			END */";$db->setQuery($query57);$db->query($query57); $query58="


			/*!50003 CREATE  PROCEDURE `GetCurrencyList`(
			  IN	lang	varchar(2)
			)
			BEGIN
				  SELECT
							c.currency_id,
					IFNULL(jf.value,c.currency_name) as currency_name,
							c.currency_code as symbol,
							c.approx,
							c.trm,
					c.default_currency
						FROM #__cp_prm_currency c
							LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
							ON (c.currency_id=jf.reference_id AND jf.reference_table = 'cp_prm_currency' AND jf.published=1)
						WHERE c.published=1
						ORDER BY trm;
			END */";$db->setQuery($query58);$db->query($query58); $query59="


			/*!50003 CREATE  PROCEDURE `GetDeliveryCities`(
			  IN	lang	varchar(2),
			  IN  param_product_id int
			)
			BEGIN

			  SELECT
				dc.city_id,
				IFNULL(jf.value,dc.city_name) as city_name
			  FROM
				#__cp_prm_cars_delivery_city dc
				LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (dc.city_id=jf.reference_id AND jf.reference_table = 'cp_prm_cars_delivery_city' AND jf.published=1)
				JOIN #__cp_cars_delivery_city cdc ON cdc.city_id=dc.city_id
			  WHERE
				dc.published=1
				AND cdc.product_id=param_product_id
			  ORDER BY
				dc.city_name;



			END */";$db->setQuery($query59);$db->query($query59); $query60="


			/*!50003 CREATE  PROCEDURE `GetFile`(
			  IN param_product_id int,
			  IN product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT *
				FROM #__cp_hotels_files
				WHERE product_id =param_product_id
				ORDER BY 	ordering;


			  
			  ELSEIF product = 'plan' THEN
				SELECT *
				FROM #__cp_plans_files
				WHERE product_id =param_product_id
				ORDER BY 	ordering;

			  
			  ELSEIF product = 'car' THEN
				SELECT *
				FROM #__cp_cars_files
				WHERE product_id =param_product_id
				ORDER BY 	ordering;

			  
			  ELSEIF product = 'transfer' THEN
				SELECT *
				FROM #__cp_transfers_files
				WHERE product_id =param_product_id
				ORDER BY ordering;

			  ELSEIF product = 'tour' THEN
				SELECT *
				FROM #__cp_tours_files
				WHERE product_id =param_product_id
				ORDER BY 	ordering;

			  
			  END IF;

			END */";$db->setQuery($query60);$db->query($query60); $query61="


			/*!50003 CREATE  PROCEDURE `GetParam1`(
			  IN lang	varchar(2),
			  IN product varchar(50),
			  IN param_product_id int
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  pr.param1_id,
				  IFNULL(jf.value,pr.param1_name) as param1_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_hotels_param1 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param1_id=jf.reference_id AND jf.reference_table = 'cp_prm_hotels_param1' AND jf.published=1)
				  JOIN #__cp_hotels_param1 pp
				  ON pp.param1_id = pr.param1_id
				WHERE pp.product_id=param_product_id;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  pr.param1_id,
				  IFNULL(jf.value,pr.param1_name) as param1_name,
				  pr.value,
				  pr.capacity,
				  pr.description
				FROM  #__cp_prm_plans_param1 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param1_id=jf.reference_id AND jf.reference_table = 'cp_prm_plans_param1' AND jf.published=1)
				  JOIN #__cp_plans_param1 pp
				  ON pp.param1_id = pr.param1_id
				WHERE pp.product_id=param_product_id;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  pr.param1_id,
				  IFNULL(jf.value,pr.param1_name) as param1_name,
				  pr.value,
				  pr.capacity,
				  pr.description
				FROM  #__cp_prm_cars_param1 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param1_id=jf.reference_id AND jf.reference_table = 'cp_prm_cars_param1' AND jf.published=1)
				  JOIN #__cp_cars_param1 pp
				  ON pp.param1_id = pr.param1_id
				WHERE pp.product_id=param_product_id;

			   
			  ELSEIF product = 'transfer' THEN
				SELECT
				  pr.param1_id,
				  IFNULL(jf.value,pr.param1_name) as param1_name,
				  pr.value,
				  pr.capacity,
				  pr.description
				FROM  #__cp_prm_transfers_param1 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param1_id=jf.reference_id AND jf.reference_table = 'cp_prm_transfers_param1' AND jf.published=1)
				  JOIN #__cp_transfers_param1 pp
				  ON pp.param1_id = pr.param1_id
				WHERE pp.product_id=param_product_id;

			  ELSEIF product = 'tour' THEN
				SELECT
				  tr.param1_id,
				  IFNULL(jf.value,tr.param1_name) as param1_name,
				  tr.value,
				  tr.capacity,
				  tr.description
				FROM  #__cp_prm_tours_param1 tr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tr.param1_id=jf.reference_id AND jf.reference_table = 'cp_prm_tours_param1' AND jf.published=1)
				  JOIN #__cp_tours_param1 tp
				  ON tp.param1_id = tr.param1_id
				WHERE tp.product_id=param_product_id;

			  END IF;

			END */";$db->setQuery($query61);$db->query($query61); $query62="


			/*!50003 CREATE  PROCEDURE `GetParam2`(
			  IN lang	varchar(2),
			  IN product varchar(50),
			  IN param_product_id int
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  pr.param2_id,
				  IFNULL(jf.value,pr.param2_name) as param2_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_hotels_param2 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param2_id=jf.reference_id AND jf.reference_table = 'cp_prm_hotels_param2' AND jf.published=1)
				  JOIN #__cp_hotels_param2 pp
				  ON pp.param2_id = pr.param2_id
				WHERE pp.product_id=param_product_id;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  pr.param2_id,
				  IFNULL(jf.value,pr.param2_name) as param2_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_plans_param2 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param2_id=jf.reference_id AND jf.reference_table = 'cp_prm_plans_param2' AND jf.published=1)
				  JOIN #__cp_plans_param2 pp
				  ON pp.param2_id = pr.param2_id
				WHERE pp.product_id=param_product_id;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  pr.param2_id,
				  IFNULL(jf.value,pr.param2_name) as param2_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_cars_param2 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param2_id=jf.reference_id AND jf.reference_table = 'cp_prm_cars_param2' AND jf.published=1)
				  JOIN #__cp_cars_param2 pp
				  ON pp.param2_id = pr.param2_id
				WHERE pp.product_id=param_product_id;

			   
			  ELSEIF product = 'transfer' THEN
				SELECT
				  pr.param2_id,
				  IFNULL(jf.value,pr.param2_name) as param2_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_transfers_param2 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param2_id=jf.reference_id AND jf.reference_table = 'cp_prm_transfers_param2' AND jf.published=1)
				  JOIN #__cp_transfers_param2 pp
				  ON pp.param2_id = pr.param2_id
				WHERE pp.product_id=param_product_id;

			  ELSEIF product = 'tour' THEN
				SELECT
				  tr.param2_id,
				  IFNULL(jf.value,tr.param2_name) as param2_name,
				  tr.value,
				  tr.capacity
				FROM  #__cp_prm_tours_param2 tr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tr.param2_id=jf.reference_id AND jf.reference_table = 'cp_prm_tours_param2' AND jf.published=1)
				  JOIN #__cp_tours_param2 tp
				  ON tp.param2_id = tr.param2_id
				WHERE tp.product_id=param_product_id;
			  END IF;


			END */";$db->setQuery($query62);$db->query($query62); $query63="


			/*!50003 CREATE  PROCEDURE `GetParam3`(
			  IN lang	varchar(2),
			  IN product varchar(50),
			  IN param_product_id int
			)
			BEGIN


			  IF product = 'hotel' THEN

				SELECT
				  pr.param3_id,
				  IFNULL(jf.value,pr.param3_name) as param3_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_hotels_param3 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param3_id=jf.reference_id AND jf.reference_table = 'cp_prm_hotels_param3' AND jf.published=1)
				  JOIN #__cp_hotels_param3 pp
				  ON pp.param3_id = pr.param3_id
				WHERE pp.product_id=param_product_id;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  pr.param3_id,
				  IFNULL(jf.value,pr.param3_name) as param3_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_plans_param3 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param3_id=jf.reference_id AND jf.reference_table = 'cp_prm_plans_param3' AND jf.published=1)
				  JOIN #__cp_plans_param3 pp
				  ON pp.param3_id = pr.param3_id
				WHERE pp.product_id=param_product_id;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  pr.param3_id,
				  IFNULL(jf.value,pr.param3_name) as param3_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_cars_param3 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param3_id=jf.reference_id AND jf.reference_table = 'cp_prm_cars_param3' AND jf.published=1)
				  JOIN #__cp_cars_param3 pp
				  ON pp.param3_id = pr.param3_id
				WHERE pp.product_id=param_product_id;

			   
			  ELSEIF product = 'transfer' THEN
				SELECT
				  pr.param3_id,
				  IFNULL(jf.value,pr.param3_name) as param3_name,
				  pr.value,
				  pr.capacity
				FROM  #__cp_prm_transfers_param3 pr
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (pr.param3_id=jf.reference_id AND jf.reference_table = 'cp_prm_transfers_param3' AND jf.published=1)
				  JOIN #__cp_transfers_param3 pp
				  ON pp.param3_id = pr.param3_id
				WHERE pp.product_id=param_product_id;


			  END IF;


			END */";$db->setQuery($query63);$db->query($query63); $query64="

			
			/*!50003 CREATE  PROCEDURE `GetSeasons`(
			  IN lang	varchar(2),
			  IN product varchar(50),
			  IN param_produtc_id int
			)
			BEGIN


			  IF product = 'hotel' THEN

			  SELECT
				s.season_id,
				s.season_name,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7
			  FROM #__cp_hotels_rate pr
				JOIN (#__cp_prm_season s
				  JOIN #__cp_prm_season_date sd
				  ON sd.season_id=s.season_id)
				ON pr.season_id=s.season_id
			  WHERE pr.product_id=param_produtc_id
			  GROUP BY season_id, start_date, end_date
			  ORDER BY season_id;


			  ELSEIF product = 'plan' THEN


			  SELECT
				s.season_id,
				s.season_name,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7
			  FROM #__cp_plans_rate pr
				JOIN (#__cp_prm_season s
				  JOIN #__cp_prm_season_date sd
				  ON sd.season_id=s.season_id)
				ON pr.season_id=s.season_id
			  WHERE pr.product_id=param_produtc_id
			  GROUP BY season_id,start_date, end_date
			  ORDER BY season_id;


			  ELSEIF product = 'car' THEN


			  SELECT
				s.season_id,
				s.season_name,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7
			  FROM #__cp_cars_rate pr
				JOIN (#__cp_prm_season s
				  JOIN #__cp_prm_season_date sd
				  ON sd.season_id=s.season_id)
				ON pr.season_id=s.season_id
			  WHERE pr.product_id=param_produtc_id
			  GROUP BY season_id,start_date, end_date
			  ORDER BY season_id;


			  ELSEIF product = 'transfer' THEN



			  SELECT
				s.season_id,
				s.season_name,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7
			  FROM #__cp_transfers_rate pr
				JOIN (#__cp_prm_season s
				  JOIN #__cp_prm_season_date sd
				  ON sd.season_id=s.season_id)
				ON pr.season_id=s.season_id
			  WHERE pr.product_id=param_produtc_id
			  GROUP BY season_id,start_date, end_date
			  ORDER BY season_id;

			  ELSEIF product = 'tour' THEN


			  SELECT
				s.season_id,
				s.season_name,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7
			  FROM #__cp_tours_rate tr
				JOIN (#__cp_prm_season s
				  JOIN #__cp_prm_season_date sd
				  ON sd.season_id=s.season_id)
				ON tr.season_id=s.season_id
			  WHERE tr.product_id=param_produtc_id
			  GROUP BY season_id,start_date, end_date
			  ORDER BY season_id;


			  END IF;

			END */";$db->setQuery($query64);$db->query($query64); $query65="


			/*!50003 CREATE  PROCEDURE `GetServiceType`(
			  IN	lang	varchar(2)
			)
			BEGIN


				SELECT
				  p.param1_id as id,
				  IFNULL(jf.value,p.param1_name) as name,
				  p.description,
				  p.capacity,
				  p.value
				FROM   #__cp_prm_transfers_param1 p
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (p.param1_id=jf.reference_id AND jf.reference_table = ' #__cp_prm_transfers_param1' AND jf.published=1)
				  JOIN #__cp_transfers_param1 hp
				  ON hp.param1_id = p.param1_id
				WHERE p.published=1;65
			END */";$db->setQuery($query65);$db->query($query65); $query66="

			
			/*!50003 CREATE  PROCEDURE `GetStock`(
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_date_finish date,
			  IN product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  hs.*
				FROM #__cp_hotels_stock hs
				WHERE
				  product_id=param_productId
				  AND hs.day between param_date_start AND param_date_finish
				ORDER BY param_id;

			  
			  ELSEIF product = 'plan' THEN

				SELECT
				  hs.*
				FROM #__cp_plans_stock hs
				WHERE
				  product_id=param_productId
				  AND hs.day = param_date_start
				ORDER BY param_id;


			  ELSEIF product = 'car' THEN

				SELECT
				  hs.*
				FROM #__cp_cars_stock hs
				WHERE
				  product_id=param_productId AND
				  hs.day between param_date_start AND param_date_finish
				ORDER BY param_id;

			  ELSEIF product = 'transfer' THEN

				SELECT
				  hs.*
				FROM #__cp_transfers_stock hs
				WHERE
				  product_id=param_productId AND
				  hs.day = param_date_start
				ORDER BY param_id;

			  ELSEIF product = 'tour' THEN

				SELECT
				  hs.*
				FROM #__cp_tours_stock hs
				WHERE
				  product_id=param_productId
				  AND hs.day = param_date_start
				ORDER BY param_id;

			  ELSEIF product = 'tour' THEN

				SELECT
				  hs.*
				FROM #__cp_tours_stock hs
				WHERE
				  product_id=param_productId
				  AND hs.day = param_date_start
				ORDER BY param_id;

			  END IF;
			END */";$db->setQuery($query66);$db->query($query66); $query67="


			/*!50003 CREATE  PROCEDURE `GetSupplementRateCar`(
			  IN apply_markup int,
			  IN param_product_id int,
			  IN param_date_start date,
			  IN param_date_finish date,
			  IN param_day1 int,
			  IN param_day2 int,
			  IN param_day3 int,
			  IN param_day4 int,
			  IN param_day5 int,
			  IN param_day6 int,
			  IN param_day7 int,
			  IN prm_group_id int

			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;




			IF apply_markup=1 THEN

			  
			  SELECT
				IFNULL(m.value, a.value) INTO pred_markup
			  FROM #__agency_group a
				LEFT JOIN #__markup m ON m.idagency_group=a.id AND m.externalid=param_product_id AND product_typeid='cars'
			  WHERE a.id=prm_group_id;

				SELECT
				  r.rate_id,
				  r.product_id,
				  rs.supplement_id,
				  rs.amount,
				  r.currency_id,
				  s.season_id,
				  s.is_special,
				  sd.start_date,
				  sd.end_date,
				  s.day1,
				  s.day2,
				  s.day3,
				  s.day4,
				  s.day5,
				  s.day6,
				  s.day7,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM #__cp_cars_rate_supplement rs
				  JOIN (#__cp_cars_rate r
					 JOIN #__cp_prm_season s
					 ON s.season_id=r.season_id
					 JOIN #__cp_prm_season_date sd
					 ON sd.season_id=r.season_id)
				  ON r.rate_id=rs.rate_id
				  JOIN #__cp_cars_supplement ps
				  ON ps.supplement_id=rs.supplement_id AND ps.product_id=r.product_id
				  LEFT JOIN #__markup_supplement mrk ON mrk.supplementid=rs.supplement_id AND mrk.externalid=r.product_id AND mrk.product_typeid='cars'
				WHERE
				  r.product_id=param_product_id
				  AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				  AND (if(param_day1=1,s.day1=1,1=2)
				  OR if(param_day2=1,s.day2=1,1=2)
				  OR if(param_day3=1,s.day3=1,1=2)
				  OR if(param_day4=1,s.day4=1,1=2)
				  OR if(param_day5=1,s.day5=1,1=2)
				  OR if(param_day6=1,s.day6=1,1=2)
				  OR if(param_day7=1,s.day7=1,1=2))
				ORDER BY
				  s.is_special desc;

			ELSE

				SELECT
				  r.rate_id,
				  r.product_id,
				  rs.supplement_id,
				  rs.amount,
				  r.currency_id,
				  s.season_id,
				  s.is_special,
				  sd.start_date,
				  sd.end_date,
				  s.day1,
				  s.day2,
				  s.day3,
				  s.day4,
				  s.day5,
				  s.day6,
				  s.day7,
				  0 as markup
				FROM #__cp_cars_rate_supplement rs
				  JOIN (#__cp_cars_rate r
					 JOIN #__cp_prm_season s
					 ON s.season_id=r.season_id
					 JOIN #__cp_prm_season_date sd
					 ON sd.season_id=r.season_id)
				  ON r.rate_id=rs.rate_id
				  JOIN #__cp_cars_supplement ps
				  ON ps.supplement_id=rs.supplement_id AND ps.product_id=r.product_id
				WHERE
				  r.product_id=param_product_id
				  AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				  AND (if(param_day1=1,s.day1=1,1=2)
				  OR if(param_day2=1,s.day2=1,1=2)
				  OR if(param_day3=1,s.day3=1,1=2)
				  OR if(param_day4=1,s.day4=1,1=2)
				  OR if(param_day5=1,s.day5=1,1=2)
				  OR if(param_day6=1,s.day6=1,1=2)
				  OR if(param_day7=1,s.day7=1,1=2))
				ORDER BY
				  s.is_special desc;

			END IF;


			END */";$db->setQuery($query67);$db->query($query67); $query68="


			/*!50003 CREATE  PROCEDURE `GetSupplementRateHotel`(
			  IN apply_markup int,
			  IN param_product_id int,
			  IN param_date_start date,
			  IN param_date_finish date,
			  IN param_day1 int,
			  IN param_day2 int,
			  IN param_day3 int,
			  IN param_day4 int,
			  IN param_day5 int,
			  IN param_day6 int,
			  IN param_day7 int,
			  IN prm_group_id int
			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;




			IF apply_markup=1 THEN

			  
			  SELECT
				IFNULL(m.value, a.value) INTO pred_markup
			  FROM #__agency_group a
				LEFT JOIN #__markup m ON m.idagency_group=a.id AND m.externalid=param_product_id AND product_typeid='hotels'
			  WHERE a.id=prm_group_id;

				SELECT
				  r.rate_id,
				  r.product_id,
				  rs.supplement_id,
				  rs.amount,
				  r.currency_id,
				  s.season_id,
				  s.is_special,
				  sd.start_date,
				  sd.end_date,
				  s.day1,
				  s.day2,
				  s.day3,
				  s.day4,
				  s.day5,
				  s.day6,
				  s.day7,
				  ps.apply_once,
				  IFNULL(mrk.value,pred_markup) as markup
				FROM #__cp_hotels_rate_supplement rs
				  JOIN (#__cp_hotels_rate r
					 JOIN #__cp_prm_season s
					 ON s.season_id=r.season_id
					 JOIN #__cp_prm_season_date sd
					 ON sd.season_id=r.season_id)
				  ON r.rate_id=rs.rate_id
				  JOIN #__cp_hotels_supplement ps
				  ON ps.supplement_id=rs.supplement_id AND ps.product_id=r.product_id
				  LEFT JOIN #__markup_supplement mrk ON mrk.supplementid=rs.supplement_id AND mrk.externalid=r.product_id AND mrk.product_typeid='hotels' 
				WHERE
				  r.product_id=param_product_id
				  AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				  AND (if(param_day1=1,s.day1=1,1=2)
				  OR if(param_day2=1,s.day2=1,1=2)
				  OR if(param_day3=1,s.day3=1,1=2)
				  OR if(param_day4=1,s.day4=1,1=2)
				  OR if(param_day5=1,s.day5=1,1=2)
				  OR if(param_day6=1,s.day6=1,1=2)
				  OR if(param_day7=1,s.day7=1,1=2))
				ORDER BY
				  s.is_special desc;

			ELSE

				SELECT
				  r.rate_id,
				  r.product_id,
				  rs.supplement_id,
				  rs.amount,
				  r.currency_id,
				  s.season_id,
				  s.is_special,
				  sd.start_date,
				  sd.end_date,
				  s.day1,
				  s.day2,
				  s.day3,
				  s.day4,
				  s.day5,
				  s.day6,
				  s.day7,
				  ps.apply_once,
				  0 as markup
				FROM #__cp_hotels_rate_supplement rs
				  JOIN (#__cp_hotels_rate r
					 JOIN #__cp_prm_season s
					 ON s.season_id=r.season_id
					 JOIN #__cp_prm_season_date sd
					 ON sd.season_id=r.season_id)
				  ON r.rate_id=rs.rate_id
				  JOIN #__cp_hotels_supplement ps
				  ON ps.supplement_id=rs.supplement_id AND ps.product_id=r.product_id
				WHERE
				  r.product_id=param_product_id
				  AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				  AND (if(param_day1=1,s.day1=1,1=2)
				  OR if(param_day2=1,s.day2=1,1=2)
				  OR if(param_day3=1,s.day3=1,1=2)
				  OR if(param_day4=1,s.day4=1,1=2)
				  OR if(param_day5=1,s.day5=1,1=2)
				  OR if(param_day6=1,s.day6=1,1=2)
				  OR if(param_day7=1,s.day7=1,1=2))
				ORDER BY
				  s.is_special desc;

			END IF;

			END */";$db->setQuery($query68);$db->query($query68); $query69="


			/*!50003 CREATE  PROCEDURE `GetSupplementRatePlan`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_day int,
			  IN prm_group_id int
			)
			BEGIN



			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_plans_rate_supplement rs
				JOIN (#__cp_plans_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
				LEFT JOIN #__markup_supplement mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='plans' 
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				0 as markup 
			  FROM #__cp_plans_rate_supplement rs
				JOIN (#__cp_plans_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			END IF;


			END */";$db->setQuery($query69);$db->query($query69); $query70="

			
			/*!50003 CREATE  PROCEDURE `GetSupplementRateTour`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_day int,
			  IN prm_group_id int
			)
			BEGIN



			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_tours_rate_supplement rs
				JOIN (#__cp_tours_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
				LEFT JOIN #__markup_supplement mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='tours' 
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				0 as markup 
			  FROM #__cp_tours_rate_supplement rs
				JOIN (#__cp_tours_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			END IF;


			END */";$db->setQuery($query70);$db->query($query70); $query71="


			/*!50003 CREATE  PROCEDURE `GetSupplementRateTransfer`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_day int,
			  IN prm_group_id int

			)
			BEGIN




			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				IFNULL(mrk.value,pred_markup) as markup
			  FROM #__cp_transfers_rate_supplement rs
				JOIN (#__cp_transfers_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
				LEFT JOIN #__markup_supplement mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='transfers'
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rs.supplement_id,
				rs.amount,
				r.currency_id,
				s.season_id,
				0 as markup 
			  FROM #__cp_transfers_rate_supplement rs
				JOIN (#__cp_transfers_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rs.rate_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc;

			END IF;



			END */";$db->setQuery($query71);$db->query($query71); $query72="

			
			/*!50003 CREATE  PROCEDURE `GetSupplements`(
			  IN lang	varchar(2),
			  IN product varchar(50),
			  IN param_product_id int
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  s.supplement_id,
				  IFNULL(jf.value,s.supplement_name) as supplement_name,
				  IFNULL(jfci.value,s.description) as description,
				  s.supplement_code,
				 s.imageurl,
				  ps.apply_once
				FROM  #__cp_prm_supplement s
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplement_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplement' AND jf.published=1 AND jf.reference_field='supplement_name')
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (s.supplement_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_supplement' AND jfci.published=1 AND jfci.reference_field='description')
				  JOIN #__cp_hotels_supplement ps
				  ON s.supplement_id = ps.supplement_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  s.supplement_id,
				  IFNULL(jf.value,s.supplement_name) as supplement_name,
				  IFNULL(jfci.value,s.description) as description,
				  s.supplement_code,
				 s.imageurl
				FROM  #__cp_prm_supplement s
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplement_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplement' AND jf.published=1 AND jf.reference_field='supplement_name')
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (s.supplement_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_supplement' AND jfci.published=1 AND jfci.reference_field='description')
				  JOIN #__cp_plans_supplement ps
				  ON s.supplement_id = ps.supplement_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			  
			  ELSEIF product = 'car' THEN
				SELECT
				  s.supplement_id,
				  IFNULL(jf.value,s.supplement_name) as supplement_name,
				  s.supplement_code,
				 s.imageurl
				FROM  #__cp_prm_supplement s
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplement_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplement' AND jf.published=1)
				  JOIN #__cp_cars_supplement ps
				  ON s.supplement_id = ps.supplement_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			   
			  ELSEIF product = 'transfer' THEN
				SELECT
				  s.supplement_id,
				  IFNULL(jf.value,s.supplement_name) as supplement_name,
				  s.supplement_code,
				 s.imageurl
				FROM  #__cp_prm_supplement s
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplement_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplement' AND jf.published=1)
				  JOIN #__cp_transfers_supplement ps
				  ON s.supplement_id = ps.supplement_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			  ELSEIF product = 'tour' THEN
				SELECT
				  s.supplement_id,
				  IFNULL(jf.value,s.supplement_name) as supplement_name,
				  IFNULL(jfci.value,s.description) as description,
				  s.supplement_code,
				 s.imageurl
				FROM  #__cp_prm_supplement s
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplement_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplement' AND jf.published=1 AND jf.reference_field='supplement_name')
				  LEFT JOIN (#__jf_content jfci
					 INNER JOIN #__languages lci
					 ON lci.lang_id=jfci.language_id AND lci.sef = lang)
				   ON (s.supplement_id=jfci.reference_id AND jfci.reference_table = 'cp_prm_supplement' AND jfci.published=1 AND jfci.reference_field='description')
				  JOIN #__cp_tours_supplement ts
				  ON s.supplement_id = ts.supplement_id
				WHERE
				  s.published=1 AND
				  ts.product_id=param_product_id;

			  END IF;


			END */";$db->setQuery($query72);$db->query($query72); $query73="

			
			/*!50003 CREATE  PROCEDURE `GetSupplementsWithMarkup`(
			  IN product varchar(50),
			  IN param_product_id int,
			  IN param_group_id int,
			  IN pred_markup double
			)
			BEGIN

			  IF product = 'hotels' THEN

				SELECT
				  s.supplement_id,
				  s.supplement_name,
				  s.description,
				  s.supplement_code,
				  IFNULL(m.value,pred_markup) as markup,
				  IFNULL(m.enabled,1) as enabled,
				  m.value
				FROM  #__cp_prm_supplement s
				  JOIN #__cp_hotels_supplement ps
				  ON s.supplement_id = ps.supplement_id
				  LEFT JOIN #__markup_supplement m ON m.supplementid=s.supplement_id AND m.externalid=ps.product_id AND m.product_typeid='hotels' AND m.idagency_group=param_group_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;


			  
			  ELSEIF product = 'plans' THEN
				SELECT
				  s.supplement_id,
				  s.supplement_name,
				  s.description,
				  s.supplement_code,
				  IFNULL(m.value,pred_markup) as markup,
				  IFNULL(m.enabled,1) as enabled
				FROM  #__cp_prm_supplement s
				  JOIN #__cp_plans_supplement ps
				  ON s.supplement_id = ps.supplement_id
				  LEFT JOIN #__markup_supplement m ON m.supplementid=s.supplement_id AND m.externalid=ps.product_id AND m.product_typeid='plans' AND m.idagency_group=param_group_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			  
			  ELSEIF product = 'cars' THEN
				SELECT
				  s.supplement_id,
				  s.supplement_name,
				  s.description,
				  s.supplement_code,
				  IFNULL(m.value,pred_markup) as markup,
				  IFNULL(m.enabled,1) as enabled
				FROM  #__cp_prm_supplement s
				  JOIN #__cp_cars_supplement ps
				  ON s.supplement_id = ps.supplement_id
				  LEFT JOIN #__markup_supplement m ON m.supplementid=s.supplement_id AND m.externalid=ps.product_id AND m.product_typeid='cars' AND m.idagency_group=param_group_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			   
			  ELSEIF product = 'transfers' THEN
				SELECT
				  s.supplement_id,
				  s.supplement_name,
				  s.description,
				  s.supplement_code,
				  IFNULL(m.value,pred_markup) as markup,
				  IFNULL(m.enabled,1) as enabled
				FROM  #__cp_prm_supplement s
				  JOIN #__cp_transfers_supplement ps
				  ON s.supplement_id = ps.supplement_id
				  LEFT JOIN #__markup_supplement m ON m.supplementid=s.supplement_id AND m.externalid=ps.product_id AND m.product_typeid='transfers' AND m.idagency_group=param_group_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;

			ELSEIF product = 'tours' THEN
				SELECT
				  s.supplement_id,
				  s.supplement_name,
				  s.description,
				  s.supplement_code,
				  IFNULL(m.value,pred_markup) as markup,
				  IFNULL(m.enabled,1) as enabled
				FROM  #__cp_prm_supplement s
				  JOIN #__cp_tours_supplement ps
				  ON s.supplement_id = ps.supplement_id
				  LEFT JOIN #__markup_supplement m ON m.supplementid=s.supplement_id AND m.externalid=ps.product_id AND m.product_typeid='tours' AND m.idagency_group=param_group_id
				WHERE
				  s.published=1 AND
				  ps.product_id=param_product_id;



			  END IF;
			END */";$db->setQuery($query73);$db->query($query73); $query74="


			/*!50003 CREATE  PROCEDURE `GetSupplier`(
			  IN	lang	varchar(2),
			  IN  param_supplier_id int
			)
			BEGIN


			  SELECT
				s.supplier_id,
				IFNULL(jf.value,s.supplier_name) as supplier_name,
				s.supplier_code,
				s.phone,
				s.fax,
				s.url,
				s.email,
				co.country_name,
				ci.city_name
			  FROM #__cp_prm_supplier s
				LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (s.supplier_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplier' AND jf.published=1)
				LEFT JOIN #__cp_prm_country co
				  ON co.country_id=s.country_id
				LEFT JOIN #__cp_prm_city ci
				  ON ci.city_id=s.city_id
			  WHERE supplier_id=param_supplier_id;


			END */";$db->setQuery($query74);$db->query($query74); $query75="


			/*!50003 CREATE  PROCEDURE `GetSupplierList`(
			  IN	lang	varchar(2),
			  IN  param_product_type int
			)
			BEGIN



				  SELECT
					s.supplier_id,
					IFNULL(jf.value,s.supplier_name) as supplier_name
				  FROM #__cp_prm_supplier s
					LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					ON (s.supplier_id=jf.reference_id AND jf.reference_table = 'cp_prm_supplier' AND jf.published=1)
					JOIN #__cp_prm_supplier_product_type pt ON pt.supplier_id=s.supplier_id
				  WHERE
					pt.product_type_id=param_product_type
				  GROUP BY s.supplier_id;


			END */";$db->setQuery($query75);$db->query($query75); $query76="

			
			/*!50003 CREATE  PROCEDURE `GetTaxesProduct`(
			  IN lang	varchar(2),
			  IN param_product_id int,
			  IN product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_hotels_taxes pt
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=pt.tax_id
				WHERE
				  pt.product_id=param_product_id
				  AND t.published=1;

			  
			  ELSEIF product = 'plan' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_plans_taxes pt
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=pt.tax_id
				WHERE
				  pt.product_id=param_product_id
				  AND t.published=1;

			  
			  ELSEIF product = 'transfer' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_transfers_taxes pt
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=pt.tax_id
				WHERE
				  pt.product_id=param_product_id
				  AND t.published=1;


			  ELSEIF product = 'car' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_cars_taxes pt
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=pt.tax_id
				WHERE
				  pt.product_id=param_product_id
				  AND t.published=1;

			  ELSEIF product = 'tour' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_tours_taxes tt
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=tt.tax_id
				WHERE
				  tt.product_id=param_product_id
				  AND t.published=1;

			  END IF;

			END */";$db->setQuery($query76);$db->query($query76); $query77="

			
			/*!50003 CREATE  PROCEDURE `GetTaxesSupplement`(
			  IN lang	varchar(2),
			  IN param_product_id int,
			  IN param_supplement_id int,
			  IN product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotel' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_hotels_supplement_tax st
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=st.tax_id
				WHERE
				  st.product_id=param_product_id
				  AND st.supplement_id = param_supplement_id
				  AND t.published=1;

			  
			  ELSEIF product = 'plan' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_plans_supplement_tax st
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=st.tax_id
				WHERE
				  st.product_id=param_product_id
				  AND st.supplement_id = param_supplement_id
				  AND t.published=1;

			  
			  ELSEIF product = 'transfer' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_transfers_supplement_tax st
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=st.tax_id
				WHERE
				  st.product_id=param_product_id
				  AND st.supplement_id = param_supplement_id
				  AND t.published=1;


			  ELSEIF product = 'car' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_cars_supplement_tax st
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=st.tax_id
				WHERE
				  st.product_id=param_product_id
				  AND st.supplement_id = param_supplement_id
				  AND t.published=1;

			  ELSEIF product = 'tour' THEN

				SELECT
				  t.tax_id,
				  IFNULL(jf.value,t.tax_name) as tax_name,
				  t.tax_code,
				  t.tax_value,
				  t.iva
				FROM #__cp_tours_supplement_tax st
				  JOIN (#__cp_prm_tax t
					 LEFT JOIN (#__jf_content jf
					  INNER JOIN #__languages l
					  ON l.lang_id=jf.language_id AND l.sef = lang)
					 ON (t.tax_id=jf.reference_id AND jf.reference_table = 'cp_prm_tax' AND jf.published=1 AND reference_field='tax_name'))
				  ON t.tax_id=st.tax_id
				WHERE
				  st.product_id=param_product_id
				  AND st.supplement_id = param_supplement_id
				  AND t.published=1;
			  END IF;

			END */";$db->setQuery($query77);$db->query($query77); $query78="

			
			/*!50003 CREATE  PROCEDURE `GetTourismType`(
			  IN	lang	varchar(2),
			  IN  product varchar(50)
			)
			BEGIN

			  
			  IF product = 'hotels' THEN

				SELECT
				  tt.tourismtype_id,
				  IFNULL(jf.value,tt.tourismtype_name) as tourismtype_name
				FROM  #__cp_prm_tourismtype tt
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tt.tourismtype_id=jf.reference_id AND jf.reference_table = 'cp_prm_tourismtype' AND jf.published=1)
				  JOIN #__cp_hotels_tourismtype ht
				  ON ht.tourismtype_id = tt.tourismtype_id
				WHERE tt.published=1
				GROUP BY tt.tourismtype_id
				ORDER BY tourismtype_name;


			  
			  ELSEIF product = 'plan' THEN
				SELECT
				  tt.tourismtype_id,
				  IFNULL(jf.value,tt.tourismtype_name) as tourismtype_name
				FROM  #__cp_prm_tourismtype tt
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tt.tourismtype_id=jf.reference_id AND jf.reference_table = 'cp_prm_tourismtype' AND jf.published=1)
				  JOIN #__cp_plans_tourismtype pt
				  ON pt.tourismtype_id = tt.tourismtype_id
				WHERE tt.published=1
				GROUP BY tt.tourismtype_id
				ORDER BY tourismtype_name;

			  ELSEIF product = 'tour' THEN
				SELECT
				  tt.tourismtype_id,
				  IFNULL(jf.value,tt.tourismtype_name) as tourismtype_name
				FROM  #__cp_prm_tourismtype tt
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tt.tourismtype_id=jf.reference_id AND jf.reference_table = 'cp_prm_tourismtype' AND jf.published=1)
				  JOIN #__cp_tours_tourismtype trt
				  ON trt.tourismtype_id = tt.tourismtype_id
				WHERE tt.published=1
				GROUP BY tt.tourismtype_id
				ORDER BY tourismtype_name;
			  
			  ELSE

				SELECT
				  tt.tourismtype_id,
				  IFNULL(jf.value,tt.tourismtype_name) as tourismtype_name
				FROM  #__cp_prm_tourismtype tt
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (tt.tourismtype_id=jf.reference_id AND jf.reference_table = 'cp_prm_tourismtype' AND jf.published=1)
				WHERE tt.published=1
				GROUP BY tt.tourismtype_id
				ORDER BY tourismtype_name;



			  END IF;
			END */";$db->setQuery($query78);$db->query($query78); $query79="


			/*!50003 CREATE  PROCEDURE `GetZone`(
			  IN	lang	varchar(2)
			)
			BEGIN




				SELECT
				  z.zone_id,
				  IFNULL(jf.value,z.zone_name) as zone_name
				FROM  #__cp_prm_zone z
				  LEFT JOIN (#__jf_content jf
					INNER JOIN #__languages l
					ON l.lang_id=jf.language_id AND l.sef = lang)
				  ON (z.zone_id=jf.reference_id AND jf.reference_table = '#__cp_prm_zone' AND jf.published=1)
				WHERE z.published=1;




			END */";$db->setQuery($query79);$db->query($query79); $query80="


			/*!50003 CREATE  PROCEDURE `LoadCarStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE, stock_quantity INT)
				COMMENT 'Asigna la cantidad dada al inventario del producto'
			BEGIN
				DECLARE x DATE;
				DECLARE exists_stock BOOLEAN;

				
				SET x = start_date;

				
				WHILE x <= end_date DO
					
					SELECT (COUNT(param_id) > 0) INTO exists_stock FROM #__cp_cars_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					IF exists_stock THEN
						UPDATE #__cp_cars_stock SET quantity = stock_quantity WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					ELSE
						INSERT INTO #__cp_cars_stock (product_id, param_id, `day`, quantity) VALUES (stock_product_id, stock_param_id, x, stock_quantity);
					END IF;

					SET x = ADDDATE(x, 1);
				END WHILE;
			END */";$db->setQuery($query80);$db->query($query80); $query81="


			/*!50003 CREATE  PROCEDURE `LoadHotelStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE, stock_quantity INT)
				COMMENT 'Asigna la cantidad dada al inventario del producto'
			BEGIN
				DECLARE x DATE;
				DECLARE exists_stock BOOLEAN;

				
				SET x = start_date;

				
				WHILE x <= end_date DO
					
					SELECT (COUNT(param_id) > 0) INTO exists_stock FROM #__cp_hotels_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					IF exists_stock THEN
						UPDATE #__cp_hotels_stock SET quantity = stock_quantity WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					ELSE
						INSERT INTO #__cp_hotels_stock (product_id, param_id, `day`, quantity) VALUES (stock_product_id, stock_param_id, x, stock_quantity);
					END IF;

					SET x = ADDDATE(x, 1);
				END WHILE;
			END */";$db->setQuery($query81);$db->query($query81); $query82="


			/*!50003 CREATE  PROCEDURE `LoadPlanStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE, stock_quantity INT)
				COMMENT 'Asigna la cantidad dada al inventario del producto'
			BEGIN
				DECLARE x DATE;
				DECLARE exists_stock BOOLEAN;

				
				SET x = start_date;

				
				WHILE x <= end_date DO
					
					SELECT (COUNT(param_id) > 0) INTO exists_stock FROM #__cp_plans_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					IF exists_stock THEN
						UPDATE #__cp_plans_stock SET quantity = stock_quantity WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					ELSE
						INSERT INTO #__cp_plans_stock (product_id, param_id, `day`, quantity) VALUES (stock_product_id, stock_param_id, x, stock_quantity);
					END IF;

					SET x = ADDDATE(x, 1);
				END WHILE;
			END */";$db->setQuery($query82);$db->query($query82); $query83="


			/*!50003 CREATE  PROCEDURE `LoadTourStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE, stock_quantity INT)
				COMMENT 'Asigna la cantidad dada al inventario del producto'
			BEGIN
				DECLARE x DATE;
				DECLARE exists_stock BOOLEAN;

				
				SET x = start_date;

				
				WHILE x <= end_date DO
					
					SELECT (COUNT(param_id) > 0) INTO exists_stock FROM #__cp_tours_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					IF exists_stock THEN
						UPDATE #__cp_tours_stock SET quantity = stock_quantity WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					ELSE
						INSERT INTO #__cp_tours_stock (product_id, param_id, `day`, quantity) VALUES (stock_product_id, stock_param_id, x, stock_quantity);
					END IF;

					SET x = ADDDATE(x, 1);
				END WHILE;
			END */";$db->setQuery($query83);$db->query($query83); $query84="


			/*!50003 CREATE  PROCEDURE `LoadTransferStock`(stock_product_id INT, stock_param_id INT, start_date DATE, end_date DATE, stock_quantity INT)
				COMMENT 'Asigna la cantidad dada al inventario del producto'
			BEGIN
				DECLARE x DATE;
				DECLARE exists_stock BOOLEAN;

				
				SET x = start_date;

				
				WHILE x <= end_date DO
					
					SELECT (COUNT(param_id) > 0) INTO exists_stock FROM #__cp_transfers_stock WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					IF exists_stock THEN
						UPDATE #__cp_transfers_stock SET quantity = stock_quantity WHERE product_id = stock_product_id AND param_id = stock_param_id AND `day` = x;
					ELSE
						INSERT INTO #__cp_transfers_stock (product_id, param_id, `day`, quantity) VALUES (stock_product_id, stock_param_id, x, stock_quantity);
					END IF;

					SET x = ADDDATE(x, 1);
				END WHILE;
			END */";$db->setQuery($query84);$db->query($query84); $query85="


			/*!50003 CREATE  PROCEDURE `RateCar`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_date_finish date,
			  IN param_day1 int,
			  IN param_day2 int,
			  IN param_day3 int,
			  IN param_day4 int,
			  IN param_day5 int,
			  IN param_day6 int,
			  IN param_day7 int,
			  IN prm_group_id int
			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_cars_rate_price rp
				JOIN (#__cp_cars_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_cars_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_cars_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__markup mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='cars' AND mrk.idagency_group=prm_group_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				AND (if(param_day1=1,s.day1=1,1=2)
				OR if(param_day2=1,s.day2=1,1=2)
				OR if(param_day3=1,s.day3=1,1=2)
				OR if(param_day4=1,s.day4=1,1=2)
				OR if(param_day5=1,s.day5=1,1=2)
				OR if(param_day6=1,s.day6=1,1=2)
				OR if(param_day7=1,s.day7=1,1=2))
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7,
				0 as markup 
			  FROM #__cp_cars_rate_price rp
				JOIN (#__cp_cars_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_cars_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_cars_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				AND (if(param_day1=1,s.day1=1,1=2)
				OR if(param_day2=1,s.day2=1,1=2)
				OR if(param_day3=1,s.day3=1,1=2)
				OR if(param_day4=1,s.day4=1,1=2)
				OR if(param_day5=1,s.day5=1,1=2)
				OR if(param_day6=1,s.day6=1,1=2)
				OR if(param_day7=1,s.day7=1,1=2))
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2;

			END IF;


			END */";$db->setQuery($query85);$db->query($query85); $query86="


			/*!50003 CREATE  PROCEDURE `RateHotel`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_date_finish date,
			  IN param_day1 int,
			  IN param_day2 int,
			  IN param_day3 int,
			  IN param_day4 int,
			  IN param_day5 int,
			  IN param_day6 int,
			  IN param_day7 int,
			  IN prm_group_id int
			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.is_child,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_hotels_rate_price rp
				JOIN (#__cp_hotels_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_hotels_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_hotels_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_hotels_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
				LEFT JOIN #__markup mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='hotels' AND mrk.idagency_group=prm_group_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				AND (if(param_day1=1,s.day1=1,1=2)
				OR if(param_day2=1,s.day2=1,1=2)
				OR if(param_day3=1,s.day3=1,1=2)
				OR if(param_day4=1,s.day4=1,1=2)
				OR if(param_day5=1,s.day5=1,1=2)
				OR if(param_day6=1,s.day6=1,1=2)
				OR if(param_day7=1,s.day7=1,1=2))
				AND if(rp.is_child=0,p1.published=1 AND p2.published=1 AND p3.published=1,p3.published=1)
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.is_child,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				sd.end_date,
				s.day1,
				s.day2,
				s.day3,
				s.day4,
				s.day5,
				s.day6,
				s.day7,
				0 as markup 
			  FROM #__cp_hotels_rate_price rp
				JOIN (#__cp_hotels_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_hotels_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_hotels_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_hotels_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND (param_date_start BETWEEN start_date AND end_date
				OR param_date_finish BETWEEN start_date AND end_date)) OR (s.is_special=1 AND (start_date BETWEEN param_date_start AND param_date_finish
				OR end_date BETWEEN param_date_start AND param_date_finish)))
				AND (if(param_day1=1,s.day1=1,1=2)
				OR if(param_day2=1,s.day2=1,1=2)
				OR if(param_day3=1,s.day3=1,1=2)
				OR if(param_day4=1,s.day4=1,1=2)
				OR if(param_day5=1,s.day5=1,1=2)
				OR if(param_day6=1,s.day6=1,1=2)
				OR if(param_day7=1,s.day7=1,1=2))
				AND if(rp.is_child=0,p1.published=1 AND p2.published=1 AND p3.published=1,p3.published=1)
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			END IF;

			END */";$db->setQuery($query86);$db->query($query86); $query87="


			/*!50003 CREATE  PROCEDURE `RatePlan`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN prm_group_id int,
			  IN param_day int
			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.is_child,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_plans_rate_price rp
				JOIN (#__cp_plans_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_plans_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_plans_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_plans_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
				LEFT JOIN #__markup mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='plans' AND mrk.idagency_group=prm_group_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

				AND if(rp.is_child=0,p1.published=1 AND p2.published=1 AND p3.published=1,p3.published=1)
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.is_child,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				0 as markup 
			  FROM #__cp_plans_rate_price rp
				JOIN (#__cp_plans_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_plans_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_plans_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_plans_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

				AND if(rp.is_child=0,p1.published=1 AND p2.published=1 AND p3.published=1,p1.published=1 AND p3.published=1)
			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			END IF;

			END */";$db->setQuery($query87);$db->query($query87); $query88="

			
			/*!50003 CREATE  PROCEDURE `RateTour`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN prm_group_id int,
			  IN param_day int
			)
			BEGIN


			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rt.param1,
				rt.param2,
				rt.param3,
				rt.is_child,
				rt.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_tours_rate_price rt
				JOIN (#__cp_tours_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rt.rate_id
				LEFT JOIN #__cp_prm_tours_param1 t1 ON t1.param1_id=rt.param1 AND t1.published=1
				LEFT JOIN #__cp_prm_tours_param2 t2 ON t2.param2_id=rt.param2 AND t2.published=1
				LEFT JOIN #__cp_prm_tours_param3 t3 ON t3.param3_id=rt.param3 AND t3.published=1
				LEFT JOIN #__markup mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='tours' AND mrk.idagency_group=prm_group_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

				ORDER BY
				s.is_special desc,
				rt.param1,
				rt.param2,
				rt.param3;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rt.param1,
				rt.param2,
				rt.param3,
				rt.is_child,
				rt.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				0 as markup 
			  FROM #__cp_tours_rate_price rt
				JOIN (#__cp_tours_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rt.rate_id
				LEFT JOIN #__cp_prm_tours_param1 t1 ON t1.param1_id=rt.param1 AND t1.published=1
				LEFT JOIN #__cp_prm_tours_param2 t2 ON t2.param2_id=rt.param2 AND t2.published=1
				LEFT JOIN #__cp_prm_tours_param3 t3 ON t3.param3_id=rt.param3 AND t3.published=1
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_day=1 THEN s.day1=1
				  WHEN param_day=2 THEN s.day2=1
				  WHEN param_day=3 THEN s.day3=1
				  WHEN param_day=4 THEN s.day4=1
				  WHEN param_day=5 THEN s.day5=1
				  WHEN param_day=6 THEN s.day6=1
				  WHEN param_day=7 THEN s.day7=1
				END

			   ORDER BY
				s.is_special desc,
				rt.param1,
				rt.param2,
				rt.param3;

			END IF;

			END */";$db->setQuery($query88);$db->query($query88); $query90="


			/*!50003 CREATE  PROCEDURE `RateTransfer`(
			  IN apply_markup int,
			  IN param_productId int,
			  IN param_date_start date,
			  IN param_date_start_number_day int,
			  IN prm_group_id int
			)
			BEGIN

			DECLARE pred_markup double DEFAULT 0;





			IF apply_markup=1 THEN

			  SELECT a.value INTO pred_markup FROM #__agency_group a WHERE a.id=prm_group_id;

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				IFNULL(mrk.value,pred_markup) as markup 
			  FROM #__cp_transfers_rate_price rp
				JOIN (#__cp_transfers_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_transfers_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_transfers_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_transfers_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
				LEFT JOIN #__markup mrk ON mrk.externalid=r.product_id AND mrk.product_typeid='transfers' AND mrk.idagency_group=prm_group_id
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_date_start_number_day=1 THEN s.day1=1
				  WHEN param_date_start_number_day=2 THEN s.day2=1
				  WHEN param_date_start_number_day=3 THEN s.day3=1
				  WHEN param_date_start_number_day=4 THEN s.day4=1
				  WHEN param_date_start_number_day=5 THEN s.day5=1
				  WHEN param_date_start_number_day=6 THEN s.day6=1
				  WHEN param_date_start_number_day=7 THEN s.day7=1
				END

			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			ELSE

			  SELECT
				r.rate_id,
				r.product_id,
				rp.param1,
				rp.param2,
				rp.param3,
				rp.price,
				r.currency_id,
				s.season_id,
				s.is_special,
				sd.start_date,
				0 as markup 
			  FROM #__cp_transfers_rate_price rp
				JOIN (#__cp_transfers_rate r
				   JOIN #__cp_prm_season s
				   ON s.season_id=r.season_id
				   JOIN #__cp_prm_season_date sd
				   ON sd.season_id=r.season_id)
				ON r.rate_id=rp.rate_id
				LEFT JOIN #__cp_prm_transfers_param1 p1 ON p1.param1_id=rp.param1 AND p1.published=1
				LEFT JOIN #__cp_prm_transfers_param2 p2 ON p2.param2_id=rp.param2 AND p2.published=1
				LEFT JOIN #__cp_prm_transfers_param3 p3 ON p3.param3_id=rp.param3 AND p3.published=1
			  WHERE
				r.product_id=param_productId
				AND ((s.is_special=0 AND param_date_start BETWEEN start_date AND end_date)
				  OR (s.is_special=1 AND param_date_start BETWEEN start_date AND end_date))
				AND CASE
				  WHEN param_date_start_number_day=1 THEN s.day1=1
				  WHEN param_date_start_number_day=2 THEN s.day2=1
				  WHEN param_date_start_number_day=3 THEN s.day3=1
				  WHEN param_date_start_number_day=4 THEN s.day4=1
				  WHEN param_date_start_number_day=5 THEN s.day5=1
				  WHEN param_date_start_number_day=6 THEN s.day6=1
				  WHEN param_date_start_number_day=7 THEN s.day7=1
				END


			  ORDER BY
				s.is_special desc,
				rp.param1,
				rp.param2,
				rp.param3;

			END IF;

			END */";$db->setQuery($query90);$db->query($query90); $query91="


			/*!50003 CREATE  PROCEDURE `ValidateCarRates`(rate_product_id INT, calc_prices BOOLEAN)
			BEGIN    
				DELETE FROM #__cp_cars_supplement_tax 
					WHERE 
						product_id = rate_product_id AND 
						supplement_id NOT IN (SELECT supplement_id FROM #__cp_cars_supplement WHERE product_id = rate_product_id);
				DELETE s FROM #__cp_cars_rate_supplement s INNER JOIN #__cp_cars_rate r
					ON s.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						s.supplement_id NOT IN (SELECT supplement_id FROM #__cp_cars_supplement WHERE product_id = rate_product_id);

				
				DELETE FROM #__cp_cars_stock 
					WHERE 
						product_id = rate_product_id AND 
						param_id NOT IN (SELECT param1_id FROM #__cp_cars_param1 WHERE product_id = rate_product_id);

				
				CALL GenerateCarsRateResumeById(rate_product_id, CURDATE(), ADDDATE(CURDATE(), 365), calc_prices);
			END */";$db->setQuery($query91);$db->query($query91); $query93="


			/*!50003 CREATE  PROCEDURE `ValidateCarRatesBySeason`(rate_season_id INT, calc_prices BOOLEAN)
			BEGIN
			DECLARE done BOOLEAN DEFAULT false;
			DECLARE rate_product_id INT;
			DECLARE start_date DATE DEFAULT CURDATE();
			DECLARE end_date DATE DEFAULT ADDDATE(CURDATE(), 365);


			DECLARE cur_car CURSOR FOR SELECT DISTINCT p.product_id FROM #__cp_cars_info p 
				INNER JOIN #__cp_cars_rate r ON p.product_id = r.product_id WHERE r.season_id = rate_season_id;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
			OPEN cur_car;

			WHILE NOT done DO 
				FETCH cur_car INTO rate_product_id;
				
				CALL GenerateCarsRateResumeById(rate_product_id, start_date, end_date, calc_prices);
			END WHILE;
			END */";$db->setQuery($query93);$db->query($query93); $query94="


			/*!50003 CREATE  PROCEDURE `ValidateHotelRates`(rate_product_id INT, calc_prices BOOLEAN)
			BEGIN

				
				DELETE p FROM #__cp_hotels_rate_price p INNER JOIN #__cp_hotels_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.is_child = 0 AND 
						p.param1 NOT IN (SELECT param1_id FROM #__cp_hotels_param1 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_hotels_rate_price p INNER JOIN #__cp_hotels_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.is_child = 0 AND 
						p.param2 NOT IN (SELECT param2_id FROM #__cp_hotels_param2 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_hotels_rate_price p INNER JOIN #__cp_hotels_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param3 NOT IN (SELECT param3_id FROM #__cp_hotels_param3 WHERE product_id = rate_product_id);
				DELETE r FROM #__cp_hotels_rate r LEFT JOIN #__cp_hotels_rate_price p
					ON r.rate_id = p.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						ISNULL(p.rate_id);

				
				DELETE FROM #__cp_hotels_supplement_tax 
					WHERE 
						product_id = rate_product_id AND 
						supplement_id NOT IN (SELECT supplement_id FROM #__cp_hotels_supplement WHERE product_id = rate_product_id);
				DELETE s FROM #__cp_hotels_rate_supplement s INNER JOIN #__cp_hotels_rate r
					ON s.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						s.supplement_id NOT IN (SELECT supplement_id FROM #__cp_hotels_supplement WHERE product_id = rate_product_id);

				
				DELETE FROM #__cp_hotels_stock 
					WHERE 
						product_id = rate_product_id AND 
						param_id NOT IN (SELECT param1_id FROM #__cp_hotels_param1 WHERE product_id = rate_product_id);

				
				CALL GenerateHotelsRateResumeById(rate_product_id, CURDATE(), ADDDATE(CURDATE(), 365), calc_prices);
			END */";$db->setQuery($query94);$db->query($query94); $query95="


			/*!50003 CREATE  PROCEDURE `ValidateHotelRatesBySeason`(rate_season_id INT, calc_prices BOOLEAN)
			BEGIN
			DECLARE done BOOLEAN DEFAULT false;
			DECLARE rate_product_id INT;
			DECLARE start_date DATE DEFAULT CURDATE();
			DECLARE end_date DATE DEFAULT ADDDATE(CURDATE(), 365);


			DECLARE cur_hotel CURSOR FOR SELECT DISTINCT p.product_id FROM #__cp_hotels_info p 
				INNER JOIN #__cp_hotels_rate r ON p.product_id = r.product_id WHERE r.season_id = rate_season_id;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
			OPEN cur_hotel;

			WHILE NOT done DO 
				FETCH cur_hotel INTO rate_product_id;
				
				CALL GenerateHotelsRateResumeById(rate_product_id, start_date, end_date, calc_prices);
			END WHILE;
			END */";$db->setQuery($query95);$db->query($query95); $query96="


			/*!50003 CREATE  PROCEDURE `ValidatePlanRates`(rate_product_id INT, calc_prices BOOLEAN)
			BEGIN

				
				DELETE p FROM #__cp_plans_rate_price p INNER JOIN #__cp_plans_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param1 NOT IN (SELECT param1_id FROM #__cp_plans_param1 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_plans_rate_price p INNER JOIN #__cp_plans_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.is_child = 0 AND 
						p.param2 NOT IN (SELECT param2_id FROM #__cp_plans_param2 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_plans_rate_price p INNER JOIN #__cp_plans_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param3 NOT IN (SELECT param3_id FROM #__cp_plans_param3 WHERE product_id = rate_product_id);

				
				DELETE FROM #__cp_plans_supplement_tax 
					WHERE 
						product_id = rate_product_id AND 
						supplement_id NOT IN (SELECT supplement_id FROM #__cp_plans_supplement WHERE product_id = rate_product_id);
				DELETE s FROM #__cp_plans_rate_supplement s INNER JOIN #__cp_plans_rate r
					ON s.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						s.supplement_id NOT IN (SELECT supplement_id FROM #__cp_plans_supplement WHERE product_id = rate_product_id);

				
				DELETE FROM #__cp_plans_stock 
					WHERE 
						product_id = rate_product_id AND 
						param_id NOT IN (SELECT param1_id FROM #__cp_plans_param1 WHERE product_id = rate_product_id);

				
				CALL GeneratePlansRateResumeById(rate_product_id, CURDATE(), ADDDATE(CURDATE(), 365), calc_prices);
			END */";$db->setQuery($query96);$db->query($query96); $query97="


			/*!50003 CREATE  PROCEDURE `ValidatePlanRatesBySeason`(rate_season_id INT, calc_prices BOOLEAN)
			BEGIN
			DECLARE done BOOLEAN DEFAULT false;
			DECLARE rate_product_id INT;
			DECLARE start_date DATE DEFAULT CURDATE();
			DECLARE end_date DATE DEFAULT ADDDATE(CURDATE(), 365);


			DECLARE cur_plan CURSOR FOR SELECT DISTINCT p.product_id FROM #__cp_plans_info p 
				INNER JOIN #__cp_plans_rate r ON p.product_id = r.product_id WHERE r.season_id = rate_season_id;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
			OPEN cur_plan;

			WHILE NOT done DO 
				FETCH cur_plan INTO rate_product_id;
				
				CALL GeneratePlansRateResumeById(rate_product_id, start_date, end_date, calc_prices);
			END WHILE;
			END */";$db->setQuery($query97);$db->query($query97); $query98="


			/*!50003 CREATE  PROCEDURE `ValidateTourRates`(rate_product_id INT, calc_prices BOOLEAN)
			BEGIN     
				DELETE FROM #__cp_tours_supplement_tax 
					WHERE 
						product_id = rate_product_id AND 
						supplement_id NOT IN (SELECT supplement_id FROM #__cp_tours_supplement WHERE product_id = rate_product_id);
				DELETE s FROM #__cp_tours_rate_supplement s INNER JOIN #__cp_tours_rate r
					ON s.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						s.supplement_id NOT IN (SELECT supplement_id FROM #__cp_tours_supplement WHERE product_id = rate_product_id);

				
				DELETE FROM #__cp_tours_stock 
					WHERE 
						product_id = rate_product_id AND 
						param_id NOT IN (SELECT param1_id FROM #__cp_tours_param1 WHERE product_id = rate_product_id);

				
				CALL GenerateToursRateResumeById(rate_product_id, CURDATE(), ADDDATE(CURDATE(), 365), calc_prices);
			END */";$db->setQuery($query98);$db->query($query98); $query99="


			/*!50003 CREATE  PROCEDURE `ValidateTourRatesBySeason`(rate_season_id INT, calc_prices BOOLEAN)
			BEGIN
			DECLARE done BOOLEAN DEFAULT false;
			DECLARE rate_product_id INT;
			DECLARE start_date DATE DEFAULT CURDATE();
			DECLARE end_date DATE DEFAULT ADDDATE(CURDATE(), 365);


			DECLARE cur_tour CURSOR FOR SELECT DISTINCT p.product_id FROM #__cp_tours_info p 
				INNER JOIN #__cp_tours_rate r ON p.product_id = r.product_id WHERE r.season_id = rate_season_id;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
			OPEN cur_tour;

			WHILE NOT done DO 
				FETCH cur_tour INTO rate_product_id;
				
				CALL GenerateToursRateResumeById(rate_product_id, start_date, end_date, calc_prices);
			END WHILE;
			END */";$db->setQuery($query99);$db->query($query99); $query100="


			/*!50003 CREATE  PROCEDURE `ValidateTransferRates`(rate_product_id INT, calc_prices BOOLEAN)
			BEGIN

				DELETE p FROM #__cp_transfers_rate_price p INNER JOIN #__cp_transfers_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param1 NOT IN (SELECT param1_id FROM #__cp_transfers_param1 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_transfers_rate_price p INNER JOIN #__cp_transfers_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param2 NOT IN (SELECT param2_id FROM #__cp_transfers_param2 WHERE product_id = rate_product_id);

				DELETE p FROM #__cp_transfers_rate_price p INNER JOIN #__cp_transfers_rate r
					ON p.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						p.param3 NOT IN (SELECT param3_id FROM #__cp_transfers_param3 WHERE product_id = rate_product_id);

				DELETE FROM #__cp_transfers_supplement_tax 
					WHERE 
						product_id = rate_product_id AND 
						supplement_id NOT IN (SELECT supplement_id FROM #__cp_transfers_supplement WHERE product_id = rate_product_id);
				DELETE s FROM #__cp_transfers_rate_supplement s INNER JOIN #__cp_transfers_rate r
					ON s.rate_id = r.rate_id 
					WHERE 
						r.product_id = rate_product_id AND 
						s.supplement_id NOT IN (SELECT supplement_id FROM #__cp_transfers_supplement WHERE product_id = rate_product_id);

				DELETE FROM #__cp_transfers_stock 
					WHERE 
						product_id = rate_product_id AND 
						param_id NOT IN (SELECT param2_id FROM #__cp_transfers_param2 WHERE product_id = rate_product_id);

				CALL GenerateTransfersRateResumeById(rate_product_id, CURDATE(), ADDDATE(CURDATE(), 365), calc_prices);
			END */";$db->setQuery($query100);$db->query($query100); $query101="

			/*!50003 CREATE  PROCEDURE `ValidateTransferRatesBySeason`(rate_season_id INT, calc_prices BOOLEAN)
			BEGIN
			DECLARE done BOOLEAN DEFAULT false;
			DECLARE rate_product_id INT;
			DECLARE start_date DATE DEFAULT CURDATE();
			DECLARE end_date DATE DEFAULT ADDDATE(CURDATE(), 365);

			DECLARE cur_transfer CURSOR FOR SELECT DISTINCT p.product_id FROM #__cp_transfers_info p 
				INNER JOIN #__cp_transfers_rate r ON p.product_id = r.product_id WHERE r.season_id = rate_season_id;
			DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
			OPEN cur_transfer;

			WHILE NOT done DO 
				FETCH cur_transfer INTO rate_product_id;

				CALL GenerateTransfersRateResumeById(rate_product_id, start_date, end_date, calc_prices);
			END WHILE;
			END */
			";
			$db->setQuery($query101);
			$db->query($query101);
			$query102="
			/*!50003 CREATE PROCEDURE `FindProductsbyTourismType`(
			  IN apply_markup int,
			  IN prm_tourismtype_id varchar(100),
			  IN prm_access int,
			  IN prm_related int,
			  IN prm_group_id int,
			  IN prm_total_items int
			)
			BEGIN
			  
			  DECLARE pred_markup double DEFAULT 0;
			  SET @arr = prm_tourismtype_id;
			  SET SQL_SELECT_LIMIT = prm_total_items;

			  IF apply_markup=1 THEN

				SELECT a.value INTO pred_markup FROM vco_agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),      
				  IFNULL(mrk.value,pred_markup) as markup,
				  p.ordering as ordering_val,
				  'plan' AS product_type
				FROM  vco_cp_plans_info p
				JOIN vco_cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN vco_cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN vco_cp_prm_country co ON co.country_id=p.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN vco_markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up < now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(pt.tourismtype_id, @arr))
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='plans' AND mrk.enabled=1,1)
				  AND if(prm_related=1,p.featured=prm_related,1)
				  AND p.access<=prm_access      

				GROUP BY p.product_id
				  
				UNION ALL

				 SELECT
				  t.product_id,
				  t.product_name,
				  t.product_desc,      
				  t.latitude,
				  t.longitude,
				  t.category_id,
				  t.featured,
				  t.product_url,
				  t.product_code,
				  t.average_rating,
				  min(tf.ordering),
				  tf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(tt.tourismtype_id)) as tourismtype,
				  count(distinct(tre.date)),      
				  IFNULL(mrk.value,pred_markup) as markup,
				  t.ordering as ordering_val,
				  'tour' AS product_type
				FROM  vco_cp_tours_info t
				JOIN vco_cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN vco_cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN vco_cp_prm_country co ON co.country_id=t.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=t.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_tours_files  ORDER BY ordering
				) tf on tf.product_id=t.product_id
				LEFT JOIN vco_markup mrk ON mrk.externalid=t.product_id AND mrk.product_typeid='tours' AND idagency_group=prm_group_id
				WHERE t.published=1      
				  AND t.publish_up < now()
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(tt.tourismtype_id, @arr)) 
				  AND if(mrk.enabled=0,mrk.externalid=t.product_id AND mrk.product_typeid='tours' AND mrk.enabled=1,1)
				  AND if(prm_related=1,t.featured=prm_related,1)
				  AND t.access<=prm_access
				
				GROUP BY t.product_id

				UNION ALL

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.latitude,
				  p.longitude,
				  p.featured,
				  p.category_id,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  MIN(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  IFNULL(mrk.value,pred_markup) as markup,
				p.ordering as ordering_val,
				'hotel' AS product_type
				FROM  vco_cp_hotels_info p
				JOIN vco_cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN vco_cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN vco_cp_prm_country co ON co.country_id=p.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_hotels_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				LEFT JOIN vco_markup mrk ON mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND idagency_group=prm_group_id
				WHERE p.published=1      
				  AND p.publish_up < now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(pt.tourismtype_id, @arr)) 
				  AND if(mrk.enabled=0,mrk.externalid=p.product_id AND mrk.product_typeid='hotels' AND mrk.enabled=1,1)
				  AND if(prm_related=1,p.featured=prm_related,1)
				  AND p.access<=prm_access

				GROUP BY p.product_id


				ORDER BY ordering_val;
			ELSE

				SELECT a.value INTO pred_markup FROM vco_agency_group a WHERE a.id=prm_group_id;

				 SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,      
				  p.latitude,
				  p.longitude,
				  p.category_id,
				  p.featured,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  p.duration as duration_text,
				  p.days_total as duration,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  p.ordering as ordering_val,
				  'plan' AS product_type      
				FROM  vco_cp_plans_info p
				JOIN vco_cp_plans_resume pre ON p.product_id=pre.product_id
				LEFT JOIN vco_cp_plans_tourismtype pt ON pt.product_id=p.product_id
				JOIN vco_cp_prm_country co ON co.country_id=p.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_plans_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up < now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())   
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(pt.tourismtype_id, @arr))
				  AND if(prm_related=1,p.featured=prm_related,1)
				  AND p.access<=prm_access

				GROUP BY p.product_id
				
				UNION ALL

				 SELECT
				  t.product_id,
				  t.product_name,
				  t.product_desc,      
				  t.latitude,
				  t.longitude,
				  t.category_id,
				  t.featured,
				  t.product_url,
				  t.product_code,
				  t.average_rating,
				  t.duration as duration_text,
				  t.days_total as duration,
				  min(tf.ordering),
				  tf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  tre.currency_id,
				  min(tre.adult_price) as basic_price,
				  min(tre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(tt.tourismtype_id)) as tourismtype,
				  count(distinct(tre.date)),
				  t.ordering as ordering_val,
				  'tour' AS product_type
				FROM  vco_cp_tours_info t
				JOIN vco_cp_tours_resume tre ON t.product_id=tre.product_id
				LEFT JOIN vco_cp_tours_tourismtype tt ON tt.product_id=t.product_id
				JOIN vco_cp_prm_country co ON co.country_id=t.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=t.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_tours_files  ORDER BY ordering
				) tf on tf.product_id=t.product_id
				WHERE t.published=1      
				  AND t.publish_up < now()
				  AND if(t.publish_down='0000-00-00',1,t.publish_down>now())       
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(tt.tourismtype_id, @arr))
				  AND if(prm_related=1,t.featured=prm_related,1)
				  AND t.access<=prm_access

				  GROUP BY t.product_id
				  
				UNION ALL
				
				SELECT
				  p.product_id,
				  p.product_name,
				  p.product_desc,
				  p.stars,
				  p.latitude,
				  p.longitude,
				  p.zone_id,
				  p.featured,
				  p.category_id,
				  p.product_url,
				  p.product_code,
				  p.average_rating,
				  min(pf.ordering),
				  pf.file_url as image_url,
				  co.country_id,
				  co.country_name,
				  ci.city_id,
				  ci.city_name,
				  pre.currency_id,
				  min(pre.adult_price) as basic_price,
				  min(pre.previous_price) as previous_value,
				  GROUP_CONCAT(distinct(pt.tourismtype_id)) as tourismtype,
				  count(distinct(pre.date)),
				  GROUP_CONCAT(distinct(pre.date)) as dates,
				  0 as markup
				FROM  vco_cp_hotels_info p
				JOIN vco_cp_hotels_resume pre ON p.product_id=pre.product_id
				LEFT JOIN vco_cp_hotels_tourismtype pt ON pt.product_id=p.product_id
				JOIN vco_cp_prm_country co ON co.country_id=p.country_id
				JOIN vco_cp_prm_city ci ON ci.city_id=p.city_id
				LEFT JOIN (
				  SELECT * FROM vco_cp_hotels_files  ORDER BY ordering
				) pf on pf.product_id=p.product_id
				WHERE p.published=1      
				  AND p.publish_up < now()
				  AND if(p.publish_down='0000-00-00',1,p.publish_down>now())
				  AND if(prm_tourismtype_id=0,1,FIND_IN_SET(pt.tourismtype_id, @arr))
				  AND if(prm_related=1,p.featured=prm_related,1)
				  AND p.access<=prm_access
				  
				GROUP BY p.product_id	
			   ORDER BY ordering_val;
			  END IF;
			END */
			";
			$db->setQuery($query102);$db->query($query102);
			
			
            if ($db->getErrorNum() > 0){
				$err = 'Intalacion de Procedimientos Almacenados Fallo<br><br><pre>'.$db->getErrorMsg().'</pre>';
				$app->enqueueMessage($err,'error');
			}else{
				$err='Intalacion de Procedimientos Almacenados Exitosa';
				$app->enqueueMessage($err,'information');
			}
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/joomfish.php')):
			//1. cp_cars_info
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_cars_info.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_cars_info.xml"); 

			//2. cp_hotels_info
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_hotels_info.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_hotels_info.xml"); 

			//3. cp_plans_info
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_plans_info.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_plans_info.xml"); 	

			//4. cp_prm_cars_category
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_cars_category.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_cars_category.xml"); 

			//5. cp_prm_cars_delivery_city
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_cars_delivery_city.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_cars_delivery_city.xml"); 

			//6. cp_prm_city
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_city.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_city.xml"); 

			//7. cp_prm_country
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_country.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_country.xml"); 

			//8. cp_prm_currency
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_currency.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_currency.xml"); 
			//9. cp_prm_hotels_amenity
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_amenity.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_hotels_amenity.xml"); 

			//10. cp_prm_hotels_category
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_category.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_hotels_category.xml"); 	

			//11. cp_prm_hotels_param1
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param1.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_hotels_param1.xml"); 

			//12. cp_prm_hotels_param2
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param2.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_hotels_param2.xml"); 

			//13. cp_prm_hotels_param3
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param3.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_hotels_param3.xml");  

			//14. cp_prm_plans_category
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_category.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_plans_category.xml"); 

			//15. cp_prm_plans_param1
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param1.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_plans_param1.xml"); 

			//16. cp_prm_plans_param2
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param2.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_plans_param2.xml"); 	

			//17. cp_prm_plans_param3
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param3.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_plans_param3.xml"); 

			//18. cp_prm_product_type
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_product_type.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_product_type.xml"); 

			//19. cp_prm_region
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_region.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_region.xml"); 

			//20. cp_prm_season
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_season.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_season.xml"); 

			//21. cp_prm_supplement
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_supplement.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_supplement.xml"); 

			//22. cp_prm_tax
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tax.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_tax.xml"); 	

			//23. cp_prm_tourismtype
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tourismtype.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_tourismtype.xml"); 	

			//24. cp_prm_tours_category
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tours_category.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_tours_category.xml"); 	

			//25. cp_prm_transfers_category
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_category.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_transfers_category.xml"); 	

			//26. cp_prm_transfers_param1
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param1.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_transfers_param1.xml"); 	

			//27. cp_prm_transfers_param2
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param2.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_transfers_param2.xml"); 	

			//28. cp_prm_transfers_param3
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param3.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_transfers_param3.xml"); 	

			//29. cp_prm_zone
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_zone.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_prm_zone.xml"); 	

			//30. cp_tours_info
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_tours_info.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_tours_info.xml"); 	

			//31. cp_transfers_info
			rename(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_transfers_info.xml", JPATH_ADMINISTRATOR."/components/com_joomfish/contentelements/cp_transfers_info.xml"); 	
			
			//Delete directory
			rmdir(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements"); 
		else:
			//Delete file on origin
			//1. cp_cars_info
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_cars_info.xml"); 

			//2. cp_hotels_info
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_hotels_info.xml"); 

			//3. cp_plans_info
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_plans_info.xml"); 	

			//4. cp_prm_cars_category
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_cars_category.xml"); 

			//5. cp_prm_cars_delivery_city
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_cars_delivery_city.xml"); 

			//6. cp_prm_city
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_city.xml"); 

			//7. cp_prm_country
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_country.xml"); 

			//8. cp_prm_currency
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_currency.xml"); 
			//9. cp_prm_hotels_amenity
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_amenity.xml"); 

			//10. cp_prm_hotels_category
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_category.xml"); 	

			//11. cp_prm_hotels_param1
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param1.xml"); 

			//12. cp_prm_hotels_param2
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param2.xml"); 

			//13. cp_prm_hotels_param3
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_hotels_param3.xml");  

			//14. cp_prm_plans_category
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_category.xml"); 

			//15. cp_prm_plans_param1
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param1.xml"); 

			//16. cp_prm_plans_param2
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param2.xml"); 	

			//17. cp_prm_plans_param3
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_plans_param3.xml"); 

			//18. cp_prm_product_type
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_product_type.xml"); 

			//19. cp_prm_region
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_region.xml"); 

			//20. cp_prm_season
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_season.xml"); 

			//21. cp_prm_supplement
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_supplement.xml"); 

			//22. cp_prm_tax
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tax.xml"); 	

			//23. cp_prm_tourismtype
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tourismtype.xml"); 	

			//24. cp_prm_tours_category
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_tours_category.xml"); 	

			//25. cp_prm_transfers_category
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_category.xml"); 	

			//26. cp_prm_transfers_param1
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param1.xml"); 	

			//27. cp_prm_transfers_param2
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param2.xml"); 	

			//28. cp_prm_transfers_param3
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_transfers_param3.xml"); 	

			//29. cp_prm_zone
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_prm_zone.xml"); 	

			//30. cp_tours_info
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_tours_info.xml"); 	

			//31. cp_transfers_info
			unlink(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements/cp_transfers_info.xml"); 	
				
			//Delete directory
			rmdir(JPATH_ADMINISTRATOR . "/components/com_catalogo_planes/contentelements");
		endif;
		
		}
 
        /**
         * Called on update
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function update($parent){
		}
 
        /**
         * Called on uninstallation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        public function uninstall($parent){
			//1. cp_cars_info
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_cars_info.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_cars_info.xml"); 

			//2. cp_hotels_info
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_hotels_info.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_hotels_info.xml"); 

			//3. cp_plans_info
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_plans_info.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_plans_info.xml"); 	

			//4. cp_prm_cars_category
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_cars_category.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_cars_category.xml"); 

			//5. cp_prm_cars_delivery_city
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_cars_delivery_city.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_cars_delivery_city.xml"); 

			//6. cp_prm_city
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_city.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_city.xml"); 

			//7. cp_prm_country
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_country.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_country.xml"); 

			//8. cp_prm_currency
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_currency.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_currency.xml"); 
			//9. cp_prm_hotels_amenity
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_hotels_amenity.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_hotels_amenity.xml"); 

			//10. cp_prm_hotels_category
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_hotels_category.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_hotels_category.xml"); 	

			//11. cp_prm_hotels_param1
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_hotels_param1.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_hotels_param1.xml"); 

			//12. cp_prm_hotels_param2
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_hotels_param2.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_hotels_param2.xml"); 

			//13. cp_prm_hotels_param3
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_hotels_param3.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_hotels_param3.xml");  

			//14. cp_prm_plans_category
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_plans_category.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_plans_category.xml"); 
				
			//15. cp_prm_plans_param1
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_plans_param1.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_plans_param1.xml"); 

			//16. cp_prm_plans_param2
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_plans_param2.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_plans_param2.xml"); 	

			//17. cp_prm_plans_param3
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_plans_param3.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_plans_param3.xml"); 

			//18. cp_prm_product_type
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_product_type.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_product_type.xml"); 

			//19. cp_prm_region
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_region.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_region.xml"); 

			//20. cp_prm_season
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_season.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_season.xml"); 

			//21. cp_prm_supplement
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_supplement.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_supplement.xml"); 
				
			//22. cp_prm_tax
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_tax.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_tax.xml"); 	
				
			//23. cp_prm_tourismtype
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_tourismtype.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_tourismtype.xml"); 	
				
			//24. cp_prm_tours_category
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_tours_category.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_tours_category.xml"); 	
				
			//25. cp_prm_transfers_category
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_transfers_category.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_transfers_category.xml"); 	
				
			//26. cp_prm_transfers_param1
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_transfers_param1.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_transfers_param1.xml"); 	
				
			//27. cp_prm_transfers_param2
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_transfers_param2.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_transfers_param2.xml"); 	

			//28. cp_prm_transfers_param3
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_transfers_param3.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_transfers_param3.xml"); 	
				
			//29. cp_prm_zone
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_prm_zone.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_prm_zone.xml"); 	
				
			//30. cp_tours_info
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_tours_info.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_tours_info.xml"); 	
				
			//31. cp_transfers_info
			if (file_exists(JPATH_ADMINISTRATOR . '/components/com_joomfish/contentelements/cp_transfers_info.xml'))
				unlink(JPATH_ADMINISTRATOR . "/components/com_joomfish/contentelements/cp_transfers_info.xml"); 
		}
}
?>