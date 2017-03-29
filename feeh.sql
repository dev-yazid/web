-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 08, 2017 at 11:31 AM
-- Server version: 5.6.33-0ubuntu0.14.04.1
-- PHP Version: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `feeh`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `module` varchar(100) NOT NULL,
  `message` varchar(200) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `brand` varchar(255) NOT NULL,
  `image` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Maruti', 'com-1.jpg', 1, '2016-09-14', '0000-00-00'),
(2, 'Suzuki', 'com-2.jpg', 1, '2016-09-07', '0000-00-00'),
(3, 'Honda', 'com-3.jpg', 1, '0000-00-00', '0000-00-00'),
(4, 'BMW', 'com-4.jpg', 1, '0000-00-00', '0000-00-00'),
(5, 'Nissan', 'com-1.jpg', 0, '2016-11-10', '2016-11-23');

-- --------------------------------------------------------

--
-- Table structure for table `brod_requests`
--

CREATE TABLE IF NOT EXISTS `brod_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image` text NOT NULL,
  `is_seller_replied` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0 not replied , 1 replied',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `brod_requests`
--

INSERT INTO `brod_requests` (`id`, `description`, `prod_id`, `brand_id`, `user_id`, `image`, `is_seller_replied`, `status`, `created_at`, `updated_at`) VALUES
(1, 'test desc', 2, 2, 2, '', '1', '1', '2017-02-28 00:00:00', '2017-02-28 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `brod_responses`
--

CREATE TABLE IF NOT EXISTS `brod_responses` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37446 ;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `state_id`, `status`, `created_at`, `updated_at`) VALUES
(37410, 'Mahayel', 3147, 1, '0000-00-00', '0000-00-00'),
(37411, 'Abha', 3149, 1, '0000-00-00', '0000-00-00'),
(37412, 'Abu ''Aris', 3149, 1, '0000-00-00', '0000-00-00'),
(37413, 'Khamis Mushayt', 3149, 1, '0000-00-00', '0000-00-00'),
(37414, 'Qal''at Bishah', 3149, 1, '0000-00-00', '0000-00-00'),
(37415, 'Ha''il', 3152, 1, '0000-00-00', '0000-00-00'),
(37416, 'Jawf', 3153, 1, '0000-00-00', '0000-00-00'),
(37417, 'Sakakah', 3153, 1, '0000-00-00', '0000-00-00'),
(37418, 'Jizan', 3154, 1, '0000-00-00', '0000-00-00'),
(37419, 'Sabya', 3154, 1, '0000-00-00', '0000-00-00'),
(37420, 'Makkah', 3155, 1, '0000-00-00', '0000-00-00'),
(37421, 'Rabig', 3155, 1, '0000-00-00', '0000-00-00'),
(37422, 'al-Hawiyah', 3155, 1, '0000-00-00', '0000-00-00'),
(37423, 'at-Ta''if', 3155, 1, '0000-00-00', '0000-00-00'),
(37424, 'Dar''iyah', 3156, 1, '0000-00-00', '0000-00-00'),
(37425, 'Najran', 3156, 1, '0000-00-00', '0000-00-00'),
(37426, 'Sharurah', 3156, 1, '0000-00-00', '0000-00-00'),
(37427, '''Unayzah', 3157, 1, '0000-00-00', '0000-00-00'),
(37428, 'Buraydah', 3157, 1, '0000-00-00', '0000-00-00'),
(37429, 'ar-Rass', 3157, 1, '0000-00-00', '0000-00-00'),
(37430, 'Tabuk', 3158, 1, '0000-00-00', '0000-00-00'),
(37431, 'Umm Lajj', 3158, 1, '0000-00-00', '0000-00-00'),
(37432, 'al-Bahah', 3160, 1, '0000-00-00', '0000-00-00'),
(37433, 'Ara''ar', 3161, 1, '0000-00-00', '0000-00-00'),
(37434, 'Rafha', 3161, 1, '0000-00-00', '0000-00-00'),
(37435, 'Turayf', 3161, 1, '0000-00-00', '0000-00-00'),
(37436, 'al-Qurayyat', 3161, 1, '0000-00-00', '0000-00-00'),
(37437, 'Yanbu', 3162, 1, '0000-00-00', '0000-00-00'),
(37438, 'al-Madinah', 3162, 1, '0000-00-00', '0000-00-00'),
(37439, '''Afif', 3163, 1, '0000-00-00', '0000-00-00'),
(37440, 'ad-Dawadimi', 3163, 1, '0000-00-00', '0000-00-00'),
(37441, 'ad-Dilam', 3163, 1, '0000-00-00', '0000-00-00'),
(37442, 'al-Kharj', 3163, 1, '0000-00-00', '0000-00-00'),
(37443, 'al-Majma''ah', 3163, 1, '0000-00-00', '0000-00-00'),
(37444, 'ar-Riyad', 3163, 1, '0000-00-00', '0000-00-00'),
(37445, 'az-Zulfi', 3163, 1, '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=192 ;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `sortname`, `name`, `status`, `created_at`, `updated_at`) VALUES
(191, 'SA', 'Saudi Arabia', 1, '0000-00-00', '2017-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `job_details`
--

CREATE TABLE IF NOT EXISTS `job_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `hired_user_id` int(11) NOT NULL,
  `job_category` text NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `job_subtitle` varchar(255) NOT NULL,
  `job_description` text NOT NULL,
  `job_comments` text NOT NULL,
  `job_keywords` text NOT NULL,
  `job_skills` varchar(500) NOT NULL DEFAULT '[]',
  `job_stage` enum('Starting','Pending','Processing','Finished') NOT NULL DEFAULT 'Finished',
  `job_documents` text,
  `job_images` varchar(500) NOT NULL DEFAULT '[]',
  `job_length` varchar(100) NOT NULL,
  `job_cost` int(100) NOT NULL,
  `final_job_cost` int(11) DEFAULT '0',
  `is_payment_relased` tinyint(1) NOT NULL DEFAULT '0',
  `proj_close_noti_freelancer` int(11) NOT NULL DEFAULT '0' COMMENT '0 default 1 Notification 2 accepted',
  `proj_close_noti_client` int(11) NOT NULL DEFAULT '0' COMMENT '0 default 1 Notification 2 accepted',
  `job_cost_max` int(11) NOT NULL,
  `job_cost_min` int(11) NOT NULL,
  `job_stattime` date NOT NULL,
  `job_endtime` date NOT NULL,
  `job_location` varchar(255) NOT NULL,
  `status` enum('Active','InActive') NOT NULL DEFAULT 'InActive',
  `job_availble_for` enum('Freelancer','Company','Company,Freelancer','') NOT NULL DEFAULT '',
  `job_submittion_date` date NOT NULL,
  `terms_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `job_details`
--

INSERT INTO `job_details` (`id`, `user_id`, `hired_user_id`, `job_category`, `job_title`, `job_subtitle`, `job_description`, `job_comments`, `job_keywords`, `job_skills`, `job_stage`, `job_documents`, `job_images`, `job_length`, `job_cost`, `final_job_cost`, `is_payment_relased`, `proj_close_noti_freelancer`, `proj_close_noti_client`, `job_cost_max`, `job_cost_min`, `job_stattime`, `job_endtime`, `job_location`, `status`, `job_availble_for`, `job_submittion_date`, `terms_conditions`, `created_at`, `updated_at`) VALUES
(1, 3, 0, '4,14', 'Magento E-commerce Developer', 'Looking For magento developer minimum 5 years of experience', 'The E-Commerce Developer will work on an on-call basis to quote out hourly projects for a single e-commerce company.\n\nThe ideal candidate is a freelancer with a flexible schedule that can accommodate working between 10 and 30 hours a month.\nThis is the expected time requirement for ongoing maintenance. Special projects quoted out at the outset of the contract may far exceed this estimate.\n\nThe web developer will develop custom functionality and hold responsibility for regular maintenance of a Magento E-Commerce website which currently contains roughly 300 active products. On-call status is required for emergency requests.', '', 'Magento Developer', '1,10', 'Starting', '["1483106645586669551a572-com-1.jpg","1483106645586669552065d-com-2.jpg","14831066455866695520895-com-3.jpg"]', 'crop_17579603371483106650.png', '700', 1000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Company', '2017-05-30', 1, '2016-12-30', '2016-12-30'),
(2, 3, 6, '5', 'Angular Js Developer', 'Looking For Angular developer minimum 5 years of experience', 'Tired of looking at the same jobs in the high-tech industry?  Are you interested in working in the wine, beer & spirits industry? Now you have a chance to use your technology skills within a fantastic company that produces many of world’s most popular alcohol brands such as Corona, Ballast Point, Svedka and Robert Mondavi.  When you join the Constellation Brands team, you can take advantage of many perks in joining this exciting business! \n \nWe are looking for FE AngularJS Developer that will focus on new product development.  This new member of the family will be given leadership opportunities to work with the business to define solutions as well as design and deliver technology solutions on both web and mobile devices.  It is a great opportunity to demonstrate your leadership and software development skills while gaining in-depth knowledge of the industry.', '', 'Angular Js Developer', '7', 'Starting', '["148310695258666a882b8d8-project-1.jpg","148310695258666a882bb41-project-2.jpg","148310695258666a882bcb1-project-3.jpg","148310695258666a882bdbe-project-4.jpg","148310695258666a882bede-project-5.jpg","148310695258666a882c02f-project-6.jpg"]', 'crop_9005741621483106960.png', '12000', 30000, 30000, 1, 0, 1, 0, 0, '2017-01-02', '0000-00-00', '39222', 'Active', 'Company', '2017-09-29', 1, '2016-12-30', '2017-01-10'),
(3, 3, 0, '4,13', 'Html / Photos hop Designer', 'Need Html5 / CSS3 / Photo shop Expert', 'Buildertrend’s mission is simple: To make building or remodeling your home an amazing experience! Founded here in Omaha in 2006, Buildertrend is a 10 year young company that is on its way to being an industry standard. Buildertrend is changing the way that homes are built not only in North America, but around the world. Using technology to bring all parties together, Buildertrend has quickly become the product of the future.The innovative solutions and team atmosphere are what makes Buildertrend one of the hottest employers in Omaha.', '', 'html edit', '1,7', 'Starting', '["14833330505869ddba6cc3c-Jellyfish.jpg"]', 'crop_4103284541483333056.png', '18', 12, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39286', 'Active', 'Company', '2017-01-09', 1, '2017-01-02', '2017-01-02'),
(4, 7, 3, '2,3', 'Sr. Business Analyst Required', 'Need More than 5 yrs expertise Business Analyst', 'Leading and driving business process improvements through the use of information technology.  This position works closely with department managers and other business leaders to identify business challenges or changes that require modifications or additions to business systems supporting those process areas.  This person would participate in defining the scope and business benefits of these initiatives.  Once initiatives/projects are prioritized by a Governance team, they will drive the cross functional collaboration to define “as-is” processes and desired “to-be” process models, facilitate decision making, then document the business requirements that need to be met in order to achieve the project objectives.  The position will then work with other Information Technology resources to determine the optimal solution that fits within the customer''s current architecture and has the lowest cost of ownership.  During the implementation phase, this person will be responsible for ensuring all business requirements are satisfied and that the solution is fully tested.  This position will also have Project Management responsibilities on some projects.', '', 'Sr Business analyst ', '12', 'Starting', '["1483443896586b8eb82d7fe-download (3).jpg"]', 'crop_4113480771483443901.png', '23', 23, 43, 0, 0, 0, 0, 0, '2017-01-03', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-01-02', 1, '2017-01-03', '2017-01-03'),
(5, 7, 0, '1,2,3,7', 'Content Writer Required', 'Need Content Writer having 4 yrs of exp in same profile', 'We’re looking for someone to help us surface the BEST stories about ICF’s work, package them up, and share them across a variety of platforms – primarily earned media but also including owned and paid channels. We need to do this well - and we need to do it at scale. This means you’ll help translate the Really Important and Complex things we do every day into stories that people at all levels in our industry can understand and care about. And you’ll do it across a wide variety of mediums – everything from executive communications and speeches to press and marketing materials and maybe even the occasional graphic novel or cartoon. Your work will help get people interested – really interested – in ICF’s brand and its ever-growing set of industry offerings in energy, the environment, public health, education, analytics and informatics and more. ', '', 'Content Writer', '15', 'Pending', '["1483443896586b8eb82d7fe-download (3).jpg"]', 'crop_7041984351483446545.png', '53', 42, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Company,Freelancer', '2017-01-02', 1, '2017-01-03', '2017-01-03'),
(6, 3, 0, '4,5,14', ' Job For IOS Developer', 'Need IOS Developer having 4 yrs of exp in same profile', 'You’re a curious and motivated iOS engineer in-the-making, maybe you have some experience working as an iOS engineer or maybe you’re a new grad. Either way, you are passionate about taking your skills to the next level and excited about diving headfirst into all things iOS (and maybe even tvOS!). You’re self-motivated, well organized, and have an unwavering commitment to for writing efficient, maintainable, and reusable code.  You’re able to work independently, but you also thrive in a collaborative, team-oriented environment.', '', ' Job For IOS Developer', '5', 'Pending', '["1483594708586ddbd42879e-test_docs.docx"]', 'crop_4482762501483594719.png', '123', 1000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-02-27', 1, '2017-01-05', '2017-01-05'),
(7, 3, 0, '4,14', 'Job For PHP Developer', 'Need PHP Developer having 4 yrs of exp in same profile', 'The PHP Developer will be responsible for interfacing with 3rd party systems, coding PHP and working with CSS and HTML. This position will work closely with front-end engineers and designers who build the "look" of the websites, while you hook up smart, well-organized functional code on the back end. Solid programming skills in a web environment is strongly preferred.', '', 'Job For PHP Developer', '1,6', 'Pending', '["1483594882586ddc8268ddb-test_docs.docx"]', 'crop_12919776531483594893.png', '2000', 40000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39222', 'Active', 'Freelancer', '2017-01-11', 1, '2017-01-05', '2017-01-05'),
(8, 3, 0, '1,2,3', 'Need HR Executive for Our company', 'Need HR Executive having 4 yrs of exp in same profile.', 'The candidate will be an integral part of the aforementioned teams and will be instrumental in executing key HR deliverables such as talent acquisition and development, employee engagement and retention, diversity, salary planning, and organizational design. The incumbent will also provide full HR generalist support to core functions.  Responsibilities will include but are not limited to:-       Provide strategic and tactical HR support to the executive leadership teams and employees. -       Create and drive programs to increase employee engagement and retention. -       Lead and facilitate change initiatives through the use of HR consultation skills. Advise and counsel managers on a variety of topics to optimize employee engagement, team development, and organizational effectiveness. -       Counsel managers and employees on HR procedures, performance, and career planning. -       Effectively execute HR processes including talent acquisition and development, Leadership Development Review (LDR), Performance Management (PFT), diversity, and compensation programs. -       Partner with employment counsel as appropriate to objectively assess and bring employee relations issues to resolution.-       Deploy and implement Human Resources policies and practices. Work closely with local HR and field HR teams to ensure business alignment and consistency in application of policies and procedures.', '', ' Need HR Executive for Our company', '13', 'Starting', '["1483595257586dddf931722-hr.jpg"]', 'crop_11119521281483595263.png', '1234', 30000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39222', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-05', '2017-01-05'),
(9, 3, 0, '1,2,7', ' Need Call Center Executive for Our company', 'Need Call Center Executive having 4 yrs of exp in same profile', 'The Customer Service Representative (CSR) assists customers with new-account enrollments, payments, inquiries, and toll bill-related questions over the phone. Additional responsibilities also include assisting with other special projects.', '', ' Need Call Center Executive for Our company', '14', 'Pending', '["1483595596586ddf4c6ac4d-callCenter.jpg"]', 'crop_2494989791483595600.png', '12345', 3500, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Company', '2017-01-25', 1, '2017-01-05', '2017-01-05'),
(10, 3, 0, '1,2,7,11', 'Job For MEAN Stack Developer', 'Need MEAN Developer having 4 yrs of exp in same profile', 'Software Engineer that can own the full lifecycle of our platform. You will be required to bring creativity and technology prowess to our solutions. We are a growing company and are seeking an innovator to grow with us. The ideal candidate is looking for an opportunity to jump into a Y Combinator funded startup and build the next phase of Squire products from the ground up. The right person will be a product/technical ambassador for Squire charged with constructing the building blocks to establish a great team, scalable solution, and technical excellence.\n\nAs a Software Engineer at Squire, you will work on specific projects critical to Squire success - switch projects as you and our fast-paced company grow and evolve. We need solid technical foundation and the right person will be versatile, display leadership qualities, built teams, and be ready to tackle new problems across the full-stack.', '', 'Job For MEAN Developer', '6,7,8,9', 'Starting', '["1483604938586e03ca20649-test_docs.docx"]', 'crop_20015936781483604948.png', '120', 9000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-04-28', 1, '2017-01-05', '2017-01-05'),
(11, 3, 0, '1,2,11', 'Call Centre Trainer required', 'Need Call Center Trainer having 4 yrs of exp in same profile', 'The Call Center Trainer is required to design, develop, facilitate and deliver course instruction to call center employees in a professional and effective manner. Proper execution of these responsibilities will improve both individual and organizational performance while building on company goals and expectations. ', '', 'Call Center Trainer', '14,16', 'Starting', '["1483605218586e04e2aa584-demoform1.pdf"]', 'crop_20253902621483605221.png', '1000', 80000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-03-30', 1, '2017-01-05', '2017-01-05'),
(12, 3, 0, '1,2,11', 'Need BDE to manage sales', 'Need BDE to manage sales min 4 yrs of exp', 'The Business Development Executive is responsible for successfully developing new business opportunities with named targeted new national multi-site customers. These business opportunities are typically financially-based, bundled business solutions which are sold at the “VP” and "C" levels within multi-site prospects. The BDE may assist other sales professionals in planning, strategizing, developing and closing complex solution opportunities with new and existing accounts. The ideal candidate will have strong knowledge and worked in a senior level position serving the multi-site sector including end use clients and segment partners. This position can be performed virtually for the right candidate. ', '', 'Need BDE to manage sales', '2,3,7,10', 'Pending', '["1483605351586e05677c245-com-1.jpg","1483605351586e05677c3a2-com-2.jpg","1483605351586e05677c4a9-com-3.jpg"]', 'crop_3373392391483605367.png', '12000', 70000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-03-30', 1, '2017-01-05', '2017-01-05'),
(13, 3, 0, '1,2,7,11', 'Network with CCNA Certified Min 10 yes of exp.', 'Need Natwork manage 10yes of exp With CCNA certified', 'Junior Network Administrator ( LAN Technician I ) and IT Certified Professional, you will assist Team Rome IT in the ongoing operation of a DoD Network Control Center. The successful candidate will work within a multi-discipline team responsible for supporting Cisco network equipment centralized around access and Core Layer devices in a campus environment for AFRL Rome. Tasks will include installation, upgrades, support and troubleshooting of network switches. In assuming this position, you will be a critical contributor to meeting NCI''s mission: To deliver innovative, cost-effective solutions and services that enable our customers to rapidly adapt to dynamic environments.', '', 'Network Engineer with CCNA Certified Min 10 yes of exp.', '17', 'Pending', '["1483443896586b8eb82d7fe-download (3).jpg"]', 'crop_10789145431483605603.png', '12000', 50000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Company,Freelancer', '2017-05-30', 1, '2017-01-05', '2017-01-05'),
(14, 7, 0, '13', 'Automation software tester Required', 'Need Automation tester with good experience in testing.', 'The Software Tester works under the direction of the System Test Manager and System Test Lead to:\nPerform system integration, regression testing, end-to-end data validation/verification testing of new enhancements and modifications to existing systems.\nAdhere to all established processes and procedures for system tests.\nCommunicate well with both internal and external customers.\nUse established procedures to document instructions for conducting testing.\nCommunicate issues identified during testing.\nProvide support for Requirements teams, specifically in the definition of use cases.\nValidate and verify that the system is operating in accordance with system requirements\nand design specifications. Write test plans, test cases and test scripts. Maintain records of test progress. Document test results, prepare incident reports and present results, as appropriate.', '', 'Automation software tester Required', '7,10', 'Pending', '["1483618777586e39d9d6029-download (2).jpg"]', 'crop_17966260241483618784.png', '223', 22222, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39224', 'Active', 'Company,Freelancer', '2017-01-12', 1, '2017-01-05', '2017-01-05'),
(15, 3, 0, '14', 'Need Software developer for Bug Fixing', 'Need Software developer for Bug Fixing having 10 yrs of exp in the it field', 'Need software developer will work on an on-call basis to quote out hourly projects for a single e-commerce company. The ideal candidate is a freelancer with a flexible schedule that can accommodate working between 10 and 30 hours a month. This is the expected time requirement for ongoing maintenance. Special projects quoted out at the outset of the contract may far exceed this estimate. The web developer will develop custom functionality and hold responsibility for regular maintenance of a Magento E-Commerce website which currently contains roughly 300 active products. On-call status is required for emergency requests  and fix the bugs ', '', 'Need Software developer for Bug Fixing', '1,3,6', 'Starting', '', 'crop_20527328301483706466.png', '100', 1000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39222', 'Active', 'Company', '2017-01-30', 1, '2017-01-06', '2017-01-06'),
(16, 3, 0, '1,10', 'Need Reception Manager', 'Required Reception Manager Female ', 'Required Reception Manager Female  candidate who can manage office in better way', '', 'Need Reception Manager', '18', 'Processing', '', 'crop_11005256221483706769.png', '1234', 1000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39223', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-06', '2017-01-06'),
(17, 3, 0, '8,14', 'Certified SEO developer', 'need SEO Google certified developer for long term work   ', 'need SEO Google developer for long term work  need SEO Google certified developer for long term work', '', 'Certified SEO developer', '12,18', 'Processing', '', 'crop_15592027411483707208.png', '2000', 230, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39224', 'Active', 'Freelancer', '2017-01-05', 1, '2017-01-06', '2017-01-06'),
(18, 3, 0, '2,11', 'Social Media Marketing expert for our company.', 'Social Media Marketing expert for our company.', 'Social Media Marketing expert for our company.', '', 'Social Media Marketing expert for our company.', '7,14', 'Pending', '', 'crop_5979932891483708082.png', '12366', 87777, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-06', '2017-01-06'),
(19, 3, 0, '4,14', 'Ecommerce site mantanance ', 'Ecommerce site mantanance  on contract basic', 'The E-Commerce Developer will work on an on-call basis to quote out hourly projects for a single e-commerce company. The ideal candidate is a freelancer with a flexible schedule that can accommodate working between 10 and 30 hours a month. This is the expected time requirement for ongoing maintenance. Special projects quoted out at the outset of the contract may far exceed this estimate. The web developer will develop custom functionality and hold responsibility for regular maintenance of a Magento E-Commerce website which currently contains roughly 300 active products. On-call status is required for emergency requests.', '', 'Ecommerce site mantanance ', '1,10', 'Starting', '', 'crop_12590825151483708571.png', '50000', 88888, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-06', '2017-01-06'),
(20, 3, 0, '17', 'Smart Service', 'Kleiner Service', 'Wer macht mir einen Smart Service für CHF 400.-?', '', 'Smart Service', '16', 'Pending', '', 'crop_17637479261483713096.png', '4', 400, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Company,Freelancer', '2017-01-30', 1, '2017-01-06', '2017-01-06'),
(21, 12, 15, '3', 'Marketing Consultant gesucht', 'Marketing Consultant', 'Für die Reorganisation unserer Firma benötigen wir externe Fachkenntnisse eines Marketing Consultant.', '', 'Marketing Consultant gesucht', '12', 'Starting', '["1484587399587d0187b3fcf-Ziele.pdf"]', 'crop_6995604981484587403.png', '100', 25000, 25000, 1, 1, 2, 0, 0, '2017-01-24', '2017-01-24', '39596', 'Active', 'Freelancer', '2017-09-29', 1, '2017-01-16', '2017-01-24'),
(22, 5, 0, '1,2', 'I am a Angular js Developer by amit', 'need SEO Google certified developer for long term work', 'dsdsadadasd ', '', 'I am a Angular js Developer by amit', '3,7,14', 'Starting', '', 'crop_5228527291485242289.png', '10', 1000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39222', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-24', '2017-01-24'),
(23, 3, 0, '1,2', 'Need Reception Manager for jobo', 'Need Reception Manager for jobo', 'Need Reception Manager for jobo Need Reception Manager for jobo Need Reception Manager for joboNeed Reception Manager for jobo', '', 'Need Reception Manager for jobo', '3,7', 'Starting', '', 'crop_9711020251485350092.png', '10', 20000, 0, 0, 0, 0, 0, 0, '0000-00-00', '0000-00-00', '39221', 'Active', 'Freelancer', '2017-01-30', 1, '2017-01-25', '2017-01-25');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `reciver_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `attachments` text NOT NULL,
  `read_atatus` enum('0','1') NOT NULL COMMENT '0 inread 1 read',
  `usertype` enum('seller','customer') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `brand` int(11) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gear lever', 1, '1', '2016-08-22', '2017-01-18'),
(2, 'Steering wheel', 1, '1', '0000-00-00', '2016-11-16'),
(3, 'Car trip meter', 1, '1', '2016-08-21', '2016-11-08'),
(4, 'Fuel gauge', 1, '1', '2016-08-23', '2016-11-08'),
(5, 'Temperature gauge', 1, '1', '2016-08-23', '2016-08-23'),
(6, 'Disk Break', 1, '1', '2017-02-07', '2017-02-07');

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3164 ;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `country_id`, `status`, `created_at`, `updated_at`) VALUES
(3146, 'Al Khobar', 191, 1, '0000-00-00', '0000-00-00'),
(3147, 'Aseer', 191, 1, '0000-00-00', '0000-00-00'),
(3148, 'Ash Sharqiyah', 191, 1, '0000-00-00', '0000-00-00'),
(3149, 'Asir', 191, 1, '0000-00-00', '0000-00-00'),
(3150, 'Central Province', 191, 1, '0000-00-00', '0000-00-00'),
(3151, 'Eastern Province', 191, 1, '0000-00-00', '0000-00-00'),
(3152, 'Ha''il', 191, 1, '0000-00-00', '0000-00-00'),
(3153, 'Jawf', 191, 1, '0000-00-00', '0000-00-00'),
(3154, 'Jizan', 191, 1, '0000-00-00', '0000-00-00'),
(3155, 'Makkah', 191, 1, '0000-00-00', '0000-00-00'),
(3156, 'Najran', 191, 1, '0000-00-00', '0000-00-00'),
(3157, 'Qasim', 191, 1, '0000-00-00', '0000-00-00'),
(3158, 'Tabuk', 191, 1, '0000-00-00', '0000-00-00'),
(3159, 'Western Province', 191, 1, '0000-00-00', '0000-00-00'),
(3160, 'al-Bahah', 191, 1, '0000-00-00', '0000-00-00'),
(3161, 'al-Hudud-ash-Shamaliyah', 191, 1, '0000-00-00', '0000-00-00'),
(3162, 'al-Madinah', 191, 1, '0000-00-00', '0000-00-00'),
(3163, 'ar-Riyad', 191, 1, '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `pay_status` tinyint(1) NOT NULL DEFAULT '0',
  `ip` text NOT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `cid`, `fid`, `pid`, `pay_status`, `ip`, `created_at`, `updated_at`) VALUES
(1, 12, 15, 21, 1, '84.73.149.111', '2017-01-24', '2017-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `profile_image` varchar(1000) COLLATE utf8_unicode_ci DEFAULT 'avatar.jpg',
  `phone_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usertype` enum('Client','Freelancer','Company','Super Admin') COLLATE utf8_unicode_ci NOT NULL,
  `is_company` enum('No','Yes','Super Admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `role` enum('Super Admin','Admin','User','Admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'User',
  `status` enum('Active','InActive') COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `terms_conditions` tinyint(1) NOT NULL DEFAULT '0',
  `is_profile_updated` tinyint(1) NOT NULL DEFAULT '0',
  `is_client_profile_updated` int(11) NOT NULL DEFAULT '0',
  `is_payment_updated` tinyint(1) NOT NULL DEFAULT '0',
  `is_client_payment_updated` int(11) NOT NULL DEFAULT '0',
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  `email_verified` enum('No','Yes') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'No',
  `email_verify_code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verify_forgot_password` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `profile_image`, `phone_number`, `usertype`, `is_company`, `role`, `status`, `password`, `terms_conditions`, `is_profile_updated`, `is_client_profile_updated`, `is_payment_updated`, `is_client_payment_updated`, `remember_token`, `created_at`, `updated_at`, `email_verified`, `email_verify_code`, `verify_forgot_password`) VALUES
(1, 'Admin', 'JobBookers', 'admin@jobbookers.com', '1484308906_images.jpeg', '1234567788', 'Super Admin', 'Super Admin', 'Super Admin', 'Active', '$2y$10$ezZKSJOjAEBXqx2HBDJ2eubrDFvMWES/4hxvsFfOz0JvlsGh8mCS6', 0, 0, 0, 0, 0, '3dgZy0ncMImir6stX1VZkWTJexTbMzKJPx7gAOpKE5pNo5nmC72slLaFoE0A', '2016-12-29', '2017-01-31', 'Yes', '', NULL),
(2, 'Amit', 'Kumar', 'amitkumar4268@gmail.com', '148310790958666e45caa0f-images_client2.jpeg', '', 'Freelancer', 'No', 'User', 'Active', '$2y$10$Stbn0SJZ3zwDC/JbA1XOTOKFdoxifz8qhWbakozAPb7WGFFeV/oDC', 0, 0, 0, 0, 0, NULL, '2016-12-30', '2016-12-30', 'No', 's28MNuOAkeTUEvj3kx', NULL),
(3, 'Rutul', 'Patel ', 'rutul@techuz.com', '148310790958666e45caa0f-images_client2.jpeg', '1234567890', 'Client', 'No', 'User', 'Active', '$2y$10$R0ec2j9b9xmC0rfjvRTfruFohLqWPIsATV6oBqerUH.49bEX3rxN2', 0, 1, 1, 1, 1, 'QgvpTF79rHCwtDgnY050iu6NEBFGYUGkkffn3iaajkMERSX97eAcGIyvJpoq', '2016-12-30', '2017-02-07', 'Yes', '', 'opPHPm6o'),
(4, 'Parag', 'Khlas', 'parag@techuz.com', '1483619991586e3e97bab10-images (1).jpg', '2343243243', 'Freelancer', 'No', 'User', 'Active', '$2y$10$xpGeEJo8ytC.qM.zH/FVy.GlDfNUrO0p1IKPGT/bEMjnb040WZGTu', 0, 1, 0, 0, 0, NULL, '2016-12-30', '2017-01-05', 'Yes', '', NULL),
(5, 'Amit', 'Kumar', 'amitg@techuz.com', '1484566426587caf9a8df22-images.jpeg', '8306062028', 'Freelancer', 'No', 'User', 'Active', '$2y$10$Xi5eGWZvZVe7yMg8rWqDsu/osMtNSDjqdhSlaDApHukky5vjLWod2', 0, 1, 1, 1, 1, 'CundBs6T9zswa5rvfCtWiKscfCV2RwksE2cMEMjExAEgxPDOR6FflHmiOtHQ', '2016-12-30', '2017-02-06', 'Yes', '', NULL),
(6, 'Nitesh', 'Jamod', 'niteshj@techuz.com', '1483354274586a30a2b43cc-enrique-iglesias-images-008.jpg', '1234567890', 'Freelancer', 'No', 'User', 'Active', '$2y$10$3SGrix41pFnAfGoVEkpnHec2JjxkuJbGswEilhGe7/Ms4QNgJKWAW', 0, 1, 0, 1, 0, NULL, '2017-01-02', '2017-01-17', 'Yes', '', NULL),
(7, 'Parag', 'Khalas', 'ritesh@techuz.com', '1483618845586e3a1dba985-images (1).jpg', '3242343242', 'Client', 'No', 'User', 'Active', '$2y$10$DwVJWkYR/gR4vP9YmeRqRejd/Wzq4t.0azFPtKkBjNahILu9tu7Yu', 0, 0, 1, 0, 1, NULL, '2017-01-03', '2017-01-05', 'Yes', '', 'Z90qQ8ye'),
(8, 'Devang', 'Patel', 'devangp@techuz.com', 'avatar.jpg', '32659845', 'Freelancer', 'No', 'User', 'Active', '$2y$10$v8.T95AuaKNuWN3yWlGTX.3NSU0bMPb6B9ifD0hLW1Ryjvcv8107C', 0, 0, 0, 0, 0, 'kWd7KL1Av6LPZ8keozFy0OQDbk7g8Z5r2osdYaRw7LvmAz9u0ovkdOPdnTay', NULL, '2017-01-25', 'Yes', NULL, NULL),
(9, 'Mrudang', 'Shah', 'mrudang@techuz.com', 'avatar.jpg', '', 'Freelancer', 'No', 'User', 'Active', '$2y$10$.sDP0AuyuanrYUlUZvp2XOKDv/47OfV.4l1WMKHmFv5hxV1dAoEnu', 0, 0, 0, 0, 0, NULL, '2017-01-04', '2017-01-04', 'Yes', '', NULL),
(10, 'Ashvin', 'Ahojlia', 'ashvin@techuz.com', '1483619901586e3e3d380a4-images (3).jpg', '2323323224', 'Freelancer', 'No', 'User', 'Active', '$2y$10$ymycfAXnY4SxoR0H17HD..qQMjazBMF6bwIwbkjfVq.MkD4vh1MOK', 0, 1, 0, 0, 0, NULL, '2017-01-05', '2017-01-05', 'Yes', '', NULL),
(11, 'Joy', 'Zalte', 'joyzalte@techuz.com', 'avatar.jpg', '', 'Client', 'No', 'User', 'Active', '$2y$10$2.9Ae52rMgpLK48gOD.Fu.xU8zzY9RedSj55Yg4HxaYUWbbSTVerO', 0, 0, 0, 0, 0, NULL, '2017-01-05', '2017-01-05', 'Yes', '', NULL),
(12, 'Thomas', 'Schneider', 'thomas.schneider@jobbookers.ch', '1484496974587ba04eb1547-Bewerbungsbild_20150908_klein.jpg', '0795996683', 'Client', 'No', 'User', 'Active', '$2y$10$ezZKSJOjAEBXqx2HBDJ2eubrDFvMWES/4hxvsFfOz0JvlsGh8mCS6', 0, 1, 1, 0, 1, NULL, '2017-01-15', '2017-02-03', 'Yes', '', ''),
(13, 'Nikola', 'Matic', 'nikola.matic2000@gmail.com', '148500353358835b0da979b-13417688_10208940080075113_4601483958568614901_n.jpg', '0791234325', 'Freelancer', 'No', 'User', 'Active', '$2y$10$ezZKSJOjAEBXqx2HBDJ2eubrDFvMWES/4hxvsFfOz0JvlsGh8mCS6', 0, 0, 1, 0, 1, NULL, '2017-01-21', '2017-02-03', 'Yes', '', NULL),
(14, 'Nikola Firmenkunde', 'Matic', 'nikola_matic2000@yahoo.de', '148527416058877c302db91-1440x900_zixpk_hd_wallpaper_215.jpg', '0793334466', 'Freelancer', 'No', 'User', 'Active', '$2y$10$ezZKSJOjAEBXqx2HBDJ2eubrDFvMWES/4hxvsFfOz0JvlsGh8mCS6', 0, 1, 1, 1, 1, 'ryzX7WdvlvnFK10jElQRRyfR3PEwnxfXsBFs6YePf3Bb8s9or3cfiVe8q2Wg', '2017-01-21', '2017-02-02', 'Yes', '', NULL),
(15, 'Roxana', 'Froehlich', 'nikola.matic@jobbookers.ch', 'avatar.jpg', '0794686544', 'Freelancer', 'No', 'User', 'Active', '$2y$10$ezZKSJOjAEBXqx2HBDJ2eubrDFvMWES/4hxvsFfOz0JvlsGh8mCS6', 0, 1, 1, 1, 0, NULL, '2017-01-24', '2017-01-24', 'Yes', '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE IF NOT EXISTS `user_profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `portfolio_images` text,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `countryId` int(11) DEFAULT NULL,
  `stateId` int(11) DEFAULT NULL,
  `street` text,
  `locationId` varchar(100) DEFAULT NULL,
  `zipcode` int(11) DEFAULT NULL,
  `sva_document` text,
  `sva_number` varchar(100) DEFAULT NULL,
  `qualifications` text,
  `skills` text NOT NULL,
  `profile_description` text NOT NULL,
  `website` varchar(100) DEFAULT NULL,
  `videos` varchar(100) DEFAULT NULL,
  `hourly_rate` varchar(100) DEFAULT NULL,
  `sms_verification_number` int(11) DEFAULT NULL,
  `school_gratuation` varchar(255) DEFAULT NULL,
  `education` varchar(255) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `uid_number` varchar(100) NOT NULL,
  `company_verification_documents` varchar(255) DEFAULT NULL,
  `company_address` text,
  `company_no_of_employer` int(11) DEFAULT NULL,
  `company_type` varchar(100) NOT NULL,
  `vat_number` varchar(100) DEFAULT '',
  `commercial_register_number` varchar(100) NOT NULL DEFAULT '',
  `invoice_address` text NOT NULL,
  `invoice_zipcode` int(11) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `delivery_zipcode` int(11) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `portfolio_images`, `gender`, `birth_date`, `countryId`, `stateId`, `street`, `locationId`, `zipcode`, `sva_document`, `sva_number`, `qualifications`, `skills`, `profile_description`, `website`, `videos`, `hourly_rate`, `sms_verification_number`, `school_gratuation`, `education`, `job_title`, `language_id`, `uid_number`, `company_verification_documents`, `company_address`, `company_no_of_employer`, `company_type`, `vat_number`, `commercial_register_number`, `invoice_address`, `invoice_zipcode`, `delivery_address`, `delivery_zipcode`, `created_at`, `updated_at`) VALUES
(1, 1, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2016-12-29', '2016-12-29'),
(2, 2, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2016-12-30', '2016-12-30'),
(3, 3, '["14831046025866615a0df99-Desert.jpg"]', 'Male', '2029-12-04', 212, 3424, '32 somewehere', '39222', 45432, NULL, '777', '3', '2,3,5,7,10,', 'I consider myself a responsible, creative, with initiative and punctuality, I accept with pleasure the challenges and goals that your organization could assign me, with good handle of the interpersonal relationships, ability to work in teams, ability to work under high pressure, so as to solve problems efficiently and achieve the goals set by the company and my work group.', '', '', '22545', NULL, '', NULL, 'Sr. Quality Analyst ', 2, '213', NULL, 'rrrrr', 4, 'Collective-Society', '121212121212121212', '676767676767676', 'wqeewewewewqe', 42343, 'wqeewewewewqe', 42343, '2016-12-30', '2017-02-03'),
(4, 4, '["1483097063586643e7420cd-download (2).jpg"]', 'Female', '2016-12-07', 212, 3425, '43543', '48315', 34653, NULL, '', '5', '3,7,', 'Ease and willingness to learn, good interpersonal relationship skills, responsible, creative, organized, ability to make tough jobs quickly, interest in professional development, extensive experience in the field of finance, leadership and decision making, knowledge about the management of the systems and the handling of the Internet, excellent knowledge about the German language, experience in the area of computer science.', 'https://www.linkedin.com/', 'https://www.linkedin.com/', '43343', NULL, 'null', NULL, 'I am a Angular js Developer', 2, '3434', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2016-12-30', '2017-01-05'),
(5, 5, '["148310736958666c29b3714-com-1.jpg","148310736958666c29bb0ba-com-2.jpg","148310736958666c29be96f-com-3.jpg","148310736958666c29c20c3-com-4.jpg","1483704917586f8a5535a6c-project-2.jpg","1483704917586f8a5564472-project-3.jpg","1483704917586f8a556852b-project-4.jpg","1483705049586f8ad99c773-crop_4103284541483333056.png"]', 'Other', '1987-02-04', 212, 3424, 'CG Road', '39221', 38005, NULL, 'null', '1', '3,7,10,', 'Professional with experience in the area of attention and service to the customer, sales and marketing, working with the productivity objectives and targets aimed at the achievements of the company’s short and long term.', 'https://www.linkedin.com/', 'https://www.youtube.com/', '45', NULL, 'MITS RAYAGADA', NULL, 'I am a Angular js Developer', 2, 'UID12345', NULL, 'CG ROAD, Ahmedabad, Gujrat ', 25, 'Single-Company', 'VAT12345', 'COM123', 'Ahmedabad, Gujrat', NULL, 'Ahmedabad, Gujrat', NULL, '2016-12-30', '2017-02-03'),
(6, 6, '["1483709715586f9d1316d50-call-center.jpg","1483709715586f9d132ee1d-HR.jpg","1483709715586f9d1342aea-html.jpg","1483709715586f9d1352d2b-ISO.jpg","1483709715586f9d136525b-magento.jpg","1483709715586f9d1375a9f-network.jpg","1483709715586f9d1386603-php.jpg","1483709715586f9d139717d-sales.jpg"]', 'Male', '1990-07-14', 212, 3427, 'B-895', '39269', 48795, NULL, '', '3', '2,3,7,10,', 'Ease and willingness to learn, good interpersonal relationship skills, responsible, creative, organized, ability to make tough jobs quickly, interest in professional development, extensive experience in the field of finance, leadership and decision making, knowledge about the management of the systems and the handling of the Internet, excellent knowledge about the German language, experience in the area of computer science.', '', '', '150', NULL, 'Indus University', NULL, 'Web Designer', 2, '1234567891025458741025632', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-02', '2017-01-06'),
(7, 7, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', 'Other', '1991-10-02', 212, 3427, '32524543', '39266', 34534, NULL, NULL, '3', '', 'Ease and willingness to learn, good interpersonal relationship skills, responsible, creative, organized, ability to make tough jobs quickly, interest in professional development, extensive experience in the field of finance, leadership and decision making, knowledge about the management of the systems and the handling of the Internet, excellent knowledge about the German language, experience in the area of computer science.', NULL, NULL, NULL, NULL, NULL, NULL, 'Software Developer', 2, '', NULL, NULL, NULL, '', '2345234', '23524524', 'dsrtrv   ', NULL, 'sgsg ', NULL, '2017-01-03', '2017-01-05'),
(8, 9, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-04', '2017-01-04'),
(9, 10, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', 'Female', '2016-12-31', 212, 3425, '32432', '48315', 32432, NULL, 'null', '5', '1,2,3,4,5', 'Professional with experience in the area of attention and service to the customer, sales and marketing, working with the productivity objectives and targets aimed at the achievements of the company’s short and long term.', 'null', 'null', '31323', NULL, '', NULL, 'node js', 2, '324324234', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-05', '2017-01-05'),
(10, 11, '["1483443676586b8ddc71f11-images (1).jpg","1483443676586b8ddcb06af-images (2).jpg"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-05', '2017-01-05'),
(11, 12, '["1484496970587ba04a981c4-IMG_1715.JPG","1484496971587ba04b83fe8-IMG_1729.JPG","1484496972587ba04c29c83-IMG_1733.JPG"]', 'Male', '1986-12-22', 212, 3424, 'Herzogstrasse 12', '39221', 5000, NULL, '', '3', '12,', 'Ich bin ein offenere kommunikativer Typ und suche immer wieder neue Projekte.', NULL, NULL, '100', NULL, NULL, NULL, 'Marketing Projekt Leiter', 2, '2342334242423', NULL, NULL, NULL, '', 'CHE-423.012.401', 'CHE-423.012.401', 'Herzogstrasse 12', NULL, 'Herzogstrasse 12', NULL, '2017-01-15', '2017-01-21'),
(12, 13, NULL, 'Male', '1990-10-21', 212, 3455, 'xystrasse 149', '39596', 8049, NULL, NULL, NULL, '', 'Meins', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 2, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-21', '2017-01-21'),
(13, 14, NULL, 'Male', '0000-00-00', 212, 3455, 'XYStrasse 21', '39596', 8049, NULL, '1234567', '4', '5,12,', 'Meine Firma ist super', 'null', 'null', '1500', NULL, 'null', NULL, 'Manager Customer Care', 2, '', NULL, NULL, NULL, '', '', '', '', NULL, '', NULL, '2017-01-21', '2017-01-24'),
(14, 15, '["1485276579588785a35a08e-Bildschirmfoto 2013-11-13 um 14.11.13.png"]', 'Female', '2016-08-16', 212, 3455, 'gugusstrasse 15', '39596', 8049, NULL, '', '3', '12,15,', 'Roxy is Roxy', NULL, NULL, '150', NULL, 'Badgelor of Stripstange', NULL, 'Product Manager', 2, 'sdfsdfsdfsdfsdfsddsdfsdfs', NULL, NULL, NULL, '', '', '', 'gugusstrasse 15', NULL, 'gugusstrasse 15', NULL, '2017-01-24', '2017-01-24');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
