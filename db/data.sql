-- -----------------------------------------------------
-- Data for table `employee_management`.`user`
-- -----------------------------------------------------
START TRANSACTION;
USE `employee_management`;
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('annie.edison@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Annie', 'Edison', 1, 'http://localhost:8080/images/annie_edison.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('troy.barnes@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Troy', 'Barnes', 1, 'http://localhost:8080/images/troy_barnes.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('abed.nadir@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Abed', 'Nadir', 1, 'http://localhost:8080/images/abed_nadir.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('britta.perry@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Britta', 'Perry', 1, 'http://localhost:8080/images/britta_perry.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('shirley.bennett@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Shirley', 'Bennett', 1, 'http://localhost:8080/images/shirley_bennett.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('jeffrey.winger@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Jeffrey', 'Winger', 1, 'http://localhost:8080/images/jeff_winger.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('pierce.hawthorne@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Pierce', 'Hawthorne', 1, 'http://localhost:8080/images/pierce_hawthorne.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('craig.pelton@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Craig', 'Pelton', 2, 'http://localhost:8080/images/craig_pelton.jpg');
INSERT INTO `employee_management`.`user` (`email`, `password`, `first_name`, `last_name`, `user_type`, `image_url`) VALUES ('benjamin.chang@mailtrap.io', 'd404559f602eab6fd602ac7680dacbfaadd13630335e951f097af3900e9de176b6db28512f2e000b9d04fba5133e8b1c6e8df59db3a8ab9d60be4b97cc9e81db', 'Benjamin', 'Chang', 2, 'http://localhost:8080/images/ben_chang.jpg');

COMMIT;

-- -----------------------------------------------------
-- Data for table `employee_management`.`applications`
-- -----------------------------------------------------
START TRANSACTION;
USE `employee_management`;
INSERT INTO `employee_management`.`applications` (`status`, `submission_date`, `start_date`, `end_date`, `requested_days`, `user_id`, `reason`) VALUES ('approved', '2020-09-10', '2020-09-15', '2020-09-20', 5, 1, 'I want to attend a conference in Florida');
INSERT INTO `employee_management`.`applications` (`status`, `submission_date`, `start_date`, `end_date`, `requested_days`, `user_id`, `reason`) VALUES ('pending', '2020-10-10', '2020-10-11', '2020-10-12', 1, 1, 'I need to visit my parents');
INSERT INTO `employee_management`.`applications` (`status`, `submission_date`, `start_date`, `end_date`, `requested_days`, `user_id`, `reason`) VALUES ('rejected', '2020-10-10', '2020-10-11', '2020-10-12', 1, 6, 'I need a vacation.');


COMMIT;