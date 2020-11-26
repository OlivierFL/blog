-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Nov 26, 2020 at 05:53 PM
-- Server version: 8.0.20
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `url_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alt_url_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url_cv` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `description`, `url_avatar`, `alt_url_avatar`, `url_cv`) VALUES
(5, 'Développeur PHP', '1604071800.jpg', 'Olivier Floch développeur PHP', '1604071309.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `id` int NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `post_id` int NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`id`, `content`, `status`, `created_at`, `updated_at`, `post_id`, `user_id`) VALUES
(1, 'Premier commentaire du blog', 'En attente de modération', '2020-10-30 17:32:02', '2020-11-19 18:24:41', 41, 33),
(2, 'Deuxième commentaire', 'Approuvé', '2020-10-30 17:34:49', '2020-10-31 17:29:08', 41, 33),
(3, 'Troisième commentaire', 'Non validé', '2020-10-30 17:36:05', '2020-10-31 14:45:19', 41, 33),
(4, 'Premier commentaire sur le deuxième article du blog', 'Approuvé', '2020-10-31 16:52:44', '2020-10-31 16:53:02', 42, 33),
(5, 'Commentaire sur le troisième article du blog', 'Approuvé', '2020-10-31 17:11:15', '2020-10-31 17:33:27', 43, 34);

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `content` text COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `cover_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alt_cover_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `title`, `content`, `slug`, `cover_img`, `alt_cover_img`, `created_at`, `updated_at`, `user_id`) VALUES
(41, 'Premier article du blog', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sit amet commodo ligula, sit amet varius magna. Praesent ligula odio, suscipit ac ex ut, aliquet commodo purus. Phasellus varius purus et ex iaculis, et ultricies sapien fringilla. Ut malesuada sapien nec bibendum vehicula. Nulla gravida orci magna, vitae lobortis tortor mollis et. Donec aliquet metus nisl, at elementum ex iaculis nec. In vel maximus dui. Cras sed convallis arcu. Phasellus blandit lorem vitae placerat pulvinar. Curabitur at commodo ante. Nulla ac auctor mauris.\r\n\r\nAenean rutrum mollis tempor. Proin a blandit lacus. Nulla posuere placerat ipsum ac consectetur. Proin at lacinia urna. In ultricies pellentesque vehicula. Vivamus lobortis nunc sed leo lobortis, pellentesque condimentum ante tempus. Etiam dignissim dignissim ante quis tempus. Vivamus malesuada, erat suscipit mollis dictum, purus eros lacinia nulla, vel interdum arcu velit at odio. In ornare vitae metus dictum dictum.\r\n\r\nCras volutpat massa tortor. In hac habitasse platea dictumst. Ut non mauris non ligula imperdiet viverra. Sed finibus eleifend sollicitudin. Etiam pretium porttitor est, quis sollicitudin risus tempus id. Sed vel malesuada dolor. Suspendisse sollicitudin blandit finibus. Pellentesque egestas nulla et urna ornare rhoncus. Sed vulputate velit et enim fringilla, nec tempor magna eleifend. Donec varius nibh eget pretium facilisis. Praesent vitae enim a odio tincidunt pulvinar. Morbi auctor urna est.\r\n\r\nNam ornare porta efficitur. Etiam vel molestie lorem. Sed fringilla aliquet nulla ac sodales. Morbi egestas dapibus nisl sit amet porta. Etiam eu neque eu nisl pretium rutrum quis eu nunc. Proin est leo, ullamcorper eu est et, placerat eleifend nisl. Etiam et ornare libero.', 'premier-article-du-blog', '1603438172.jpg', 'Image illustration premier article du blog', '2020-10-23 07:29:32', '2020-10-23 07:29:32', 33),
(42, 'Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam efficitur, odio at consequat fringilla, ligula risus euismod metus, in dapibus elit orci id est. Maecenas dignissim eu elit placerat volutpat. Curabitur blandit auctor lobortis. Pellentesque vel justo feugiat, hendrerit orci ac, sodales quam. In et dolor metus. Proin pharetra, dolor lobortis commodo egestas, purus nulla pulvinar nisi, lacinia scelerisque felis lacus quis metus. Phasellus venenatis, arcu ut mollis sodales, metus nibh consequat orci, id tincidunt turpis magna sed justo. Pellentesque mattis tincidunt dapibus. Etiam blandit finibus orci nec elementum. Cras nec nisl est.\r\n\r\nSuspendisse nec eros faucibus, consequat mi iaculis, suscipit nunc. Suspendisse lacinia malesuada tortor, sed fermentum quam sollicitudin quis. Sed sollicitudin, leo ut lobortis malesuada, massa ex mollis ex, non ultrices lacus erat vitae sem. Ut vel justo malesuada, egestas quam nec, aliquet est. Praesent maximus, nunc a bibendum efficitur, ligula enim pellentesque ipsum, ut mollis metus est sed lorem. Nam tincidunt dolor sed sollicitudin gravida. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.\r\n\r\nMorbi euismod pharetra dolor, et pharetra mauris placerat quis. Nunc et pulvinar dolor. Sed semper mi lacus, non tristique ipsum accumsan a. Vestibulum sit amet enim et urna maximus bibendum vitae bibendum dui. Vivamus et est ullamcorper, molestie magna eget, mollis libero. Mauris aliquet ultricies nunc nec feugiat. Aenean sed erat id sapien tristique blandit ut eu lacus. Aliquam tellus purus, volutpat nec gravida non, scelerisque euismod turpis.\r\n\r\nPhasellus suscipit mollis massa, vel interdum libero interdum eu. Vestibulum congue libero ante, ac sagittis diam commodo et. Mauris feugiat justo vitae fermentum cursus. Donec bibendum ac eros quis porttitor. Donec a velit ut sem condimentum viverra vel sed metus. Vivamus ac ullamcorper mauris, vitae maximus eros. Integer eget bibendum turpis. Cras facilisis diam commodo velit sollicitudin pulvinar. Pellentesque ex sapien, vulputate eu mi id, aliquet commodo arcu. Quisque egestas porta neque aliquam venenatis. Nam lobortis vestibulum maximus.\r\n\r\nCras in arcu lacus. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Duis ultricies, dui vitae vestibulum porta, mi diam vehicula sem, quis pellentesque urna felis non velit. Vestibulum fringilla metus turpis, sit amet rutrum magna mattis nec. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nunc volutpat, orci et finibus hendrerit, erat elit molestie felis, ut accumsan nisi dolor in mauris. Vivamus volutpat eget libero vitae ullamcorper. Nullam turpis est, dignissim a orci nec, elementum bibendum turpis.', 'lorem-ipsum', '1603438251.jpg', 'Capture d\'écran éditeur de code', '2020-10-23 07:30:50', '2020-10-23 07:30:50', 33),
(43, 'Troisième article', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis tincidunt justo vitae suscipit mattis. Integer nisl turpis, lacinia et ante at, tincidunt condimentum dui. Cras tempus orci est, eget molestie dui sollicitudin eleifend. Phasellus diam tellus, pellentesque at efficitur id, vulputate vitae dui. Duis id rutrum ante. Pellentesque commodo commodo metus, a ultrices urna. Nullam volutpat vulputate ligula sit amet consequat. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.\r\n\r\nCras in erat nec turpis consectetur varius. Sed iaculis erat eu felis rutrum, ut rhoncus orci ullamcorper. Aliquam sit amet ullamcorper nulla. Integer molestie at tortor at rutrum. Vivamus at tellus sit amet ipsum pharetra auctor. Suspendisse nisl massa, lobortis vitae varius sit amet, suscipit a dui. Suspendisse hendrerit interdum molestie. Donec malesuada nisl sed dolor porta, eu ultrices lorem pharetra. Donec vestibulum sagittis nulla nec vehicula. Integer quis est vel erat condimentum tincidunt. Praesent commodo nisi erat, et posuere nulla dapibus ac. Curabitur lobortis id magna ullamcorper venenatis. Nulla tempus sapien a ante facilisis, viverra ornare erat mattis. Pellentesque aliquet lobortis risus, nec posuere arcu. Proin vitae efficitur est, nec rutrum tellus. Vestibulum venenatis mi tortor.\r\n\r\nVivamus massa quam, vulputate ullamcorper feugiat quis, viverra id leo. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Quisque non libero hendrerit, dignissim est sit amet, pulvinar ipsum. Nulla finibus ipsum velit, quis pretium tellus vehicula quis. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. In sit amet faucibus nibh, nec lobortis lectus. Vestibulum condimentum enim ac odio interdum, at fermentum nunc pharetra. Vestibulum et sem nunc. Praesent vulputate sapien velit, sit amet laoreet diam pretium ut. Nulla facilisi. In ultricies eros semper metus sodales, id imperdiet neque molestie. Vivamus congue, risus nec laoreet iaculis, neque mauris laoreet mauris, non suscipit orci lacus at nibh. Donec finibus odio eget sem malesuada efficitur. Duis sagittis elit ut nisl pharetra, ut vestibulum est volutpat. Duis a gravida nisi.\r\n\r\nSed ut consectetur turpis. Cras maximus massa a sapien laoreet vehicula at eu ante. In tempus consequat lorem, non varius tellus condimentum nec. In sit amet ligula tempor tortor hendrerit ultrices ut non tellus. Sed porttitor, risus non tincidunt ullamcorper, nisi lacus accumsan eros, nec scelerisque libero augue fermentum nisl. Aliquam a metus id neque pretium dignissim ac a libero. Vestibulum tristique sed augue sit amet iaculis. Ut lacinia, diam vel aliquet efficitur, magna sem rhoncus eros, id rutrum dui ipsum suscipit enim. Sed facilisis sem at odio elementum, vitae pretium augue ornare. Sed vehicula a arcu id interdum. Cras id velit massa. Pellentesque sodales ligula egestas felis rutrum ornare. Ut vitae sem ex. Integer at ornare leo. Integer quis odio nec est volutpat lacinia. Pellentesque non ipsum sed quam pulvinar hendrerit.\r\n\r\nIn condimentum justo massa, ut consequat massa dapibus malesuada. Ut convallis tellus velit, ut elementum massa tempus non. Mauris fringilla lacus eu purus auctor efficitur. Maecenas at faucibus ante. Fusce sed faucibus neque. Proin elementum justo augue. Nulla et ex at lacus pretium molestie. In id nisi nibh. Aenean eu rhoncus odio. Proin ornare venenatis leo, in mollis urna blandit nec. Phasellus vitae efficitur justo. Ut quis vestibulum nisi, ac convallis urna.\r\n\r\nNam accumsan purus id turpis placerat, in gravida odio dictum. Sed blandit sed massa et mollis. Vivamus et laoreet mauris. In dictum nisi ut volutpat tempus. Duis ultricies justo eget luctus facilisis. Proin diam velit, tempus nec velit quis, facilisis suscipit eros. Donec feugiat a magna at interdum. Sed eget vehicula est, non dictum risus. Maecenas a nisl lobortis, porttitor nunc non, dapibus lorem. Duis elit risus, lacinia in felis vel, bibendum commodo eros.\r\n\r\nMorbi dapibus condimentum dolor, sodales congue lacus blandit quis. Praesent porttitor, nisi non feugiat tempus, tellus erat pellentesque eros, in molestie ante purus at massa. Quisque magna justo, laoreet ut sem sit amet, fermentum ultricies mauris. Duis convallis ante eget orci tempus, sed malesuada nisi congue. Duis vel lacus et enim tristique porttitor sit amet vitae erat. Cras consequat lorem vel nulla luctus, a aliquam magna feugiat. Nunc posuere ex et ipsum tempus finibus. Duis nec massa feugiat, lobortis diam porttitor, dapibus massa. Nam sit amet arcu sit amet neque commodo pulvinar. Vestibulum pulvinar odio et arcu eleifend facilisis. Mauris sagittis urna elit, a tempus eros pretium ac. Aenean vitae scelerisque purus, eu gravida metus. Donec dapibus dapibus tortor consectetur porttitor.', 'troisieme-article', '1603438326.jpg', 'Git branches', '2020-10-23 07:32:06', '2020-11-19 18:24:12', 33),
(57, 'Lorem ipsum', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque id mollis nisi. Mauris vitae mi viverra nisi cursus efficitur in non justo. Proin dolor enim, auctor eu lorem id, efficitur mollis arcu. Mauris non risus massa. Donec tortor risus, fermentum vel hendrerit sed, pellentesque vitae enim. Etiam eu semper mauris. Nullam ac ullamcorper magna, porta dictum diam. Ut ac mauris ac nunc ornare fermentum non tempor odio. Proin eu dignissim nulla. Aliquam ligula dui, mattis quis risus non, finibus aliquet felis. Nulla facilisi. Nunc eget convallis mi. Aenean non nisi gravida, pretium felis et, condimentum nulla. Nunc eros tortor, cursus sit amet dignissim iaculis, pulvinar vitae tortor.\r\n\r\nNam vel convallis velit. Mauris at lorem nec diam fringilla pretium ultrices ut lectus. Phasellus lacinia porta mi, at consequat ligula tempor at. Vestibulum dictum ipsum non dui auctor euismod. Etiam elementum tempor mauris, ut aliquam magna euismod non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Aliquam lacinia, nunc at porttitor vulputate, metus turpis accumsan turpis, quis consequat eros magna eget nunc. Duis at nisl ac est porttitor pharetra. In efficitur convallis ligula, ac tincidunt elit mattis sit amet. Ut vitae dui eget ligula porttitor iaculis et eu mauris. Integer molestie purus nisl, in volutpat lectus hendrerit in. Donec aliquam eros vitae diam rutrum consequat.\r\n\r\nCras suscipit lacus diam, vel vulputate orci suscipit eu. Vestibulum blandit nunc id fermentum ornare. Sed non enim quis sem fringilla efficitur. Donec nec metus leo. Duis nisi arcu, eleifend ac velit ac, vestibulum feugiat lorem. Pellentesque quis finibus risus. Duis facilisis, ante ut hendrerit dapibus, libero diam vulputate leo, sit amet fringilla ipsum sapien a sapien. Suspendisse potenti. Nullam at leo ut sem vulputate tincidunt.\r\n\r\nMauris aliquam est a magna dapibus viverra. Vivamus faucibus pulvinar ullamcorper. Cras scelerisque vehicula leo sed efficitur. Fusce scelerisque arcu at nunc malesuada interdum. Nulla interdum diam tempor quam venenatis venenatis. Sed egestas blandit sem, at porta felis. Mauris auctor ut elit eu egestas.\r\n\r\nNunc ligula leo, egestas non rutrum ut, accumsan nec metus. Nunc turpis eros, elementum vitae feugiat in, aliquet at turpis. Nam in nunc lorem. Maecenas sed tortor nibh. Praesent blandit rhoncus quam eu tempus. Ut at metus aliquet, sodales ante ac, mollis mi. Duis porttitor ante eu metus tincidunt aliquam. Donec vitae erat ac massa venenatis convallis sit amet sit amet odio. Pellentesque sodales elit ut varius tincidunt. Fusce eu pulvinar justo. Cras ante erat, convallis quis eros a, finibus sodales enim. Suspendisse hendrerit nisl augue, eget suscipit sapien rhoncus a. Vestibulum pretium ultrices felis, vel hendrerit justo posuere ut.\r\n\r\nDuis congue et odio in fringilla. Pellentesque efficitur mi id urna aliquet accumsan. Morbi magna enim, tincidunt sit amet tincidunt at, placerat eu lectus. Suspendisse luctus eget dui vitae eleifend. Nulla sed orci diam. Maecenas tempor leo purus, at euismod felis rhoncus at. In eros mauris, scelerisque at libero eu, ultrices semper nunc. Morbi a dictum felis. Mauris iaculis urna augue, ac tincidunt eros dictum vel. Integer ut pulvinar purus, id interdum sem. Proin quis ultrices neque. Sed pretium leo eu mi mattis, sit amet aliquam quam tincidunt.\r\n\r\nNam ultrices lorem eu feugiat ultricies. Pellentesque vehicula libero in erat viverra imperdiet. Sed ultricies vel eros quis luctus. In a eros congue, laoreet nulla quis, auctor velit. Donec facilisis semper vestibulum. In consequat risus eu elit tempus, eget ultricies libero convallis. Nam congue urna id efficitur auctor. Pellentesque ut venenatis nisl. Nullam lorem eros, luctus vitae vestibulum et, vehicula vitae ante. Vestibulum lobortis sem ac lectus gravida, sit amet cursus mi pharetra. Fusce semper luctus nulla in pulvinar. Sed euismod ac nibh id vulputate. Nulla at mauris quam. Aenean sed egestas orci.', 'lorem-ipsum-1', NULL, 'Image illustration', '2020-11-20 16:42:40', '2020-11-20 16:42:40', 33),
(58, 'PHP 8', 'Article sur PHP 8 et ses nouveautés\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque id mollis nisi. Mauris vitae mi viverra nisi cursus efficitur in non justo. Proin dolor enim, auctor eu lorem id, efficitur mollis arcu. Mauris non risus massa. Donec tortor risus, fermentum vel hendrerit sed, pellentesque vitae enim. Etiam eu semper mauris. Nullam ac ullamcorper magna, porta dictum diam. Ut ac mauris ac nunc ornare fermentum non tempor odio. Proin eu dignissim nulla. Aliquam ligula dui, mattis quis risus non, finibus aliquet felis. Nulla facilisi. Nunc eget convallis mi. Aenean non nisi gravida, pretium felis et, condimentum nulla. Nunc eros tortor, cursus sit amet dignissim iaculis, pulvinar vitae tortor.\r\n\r\nNam vel convallis velit. Mauris at lorem nec diam fringilla pretium ultrices ut lectus. Phasellus lacinia porta mi, at consequat ligula tempor at. Vestibulum dictum ipsum non dui auctor euismod. Etiam elementum tempor mauris, ut aliquam magna euismod non. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Aliquam lacinia, nunc at porttitor vulputate, metus turpis accumsan turpis, quis consequat eros magna eget nunc. Duis at nisl ac est porttitor pharetra. In efficitur convallis ligula, ac tincidunt elit mattis sit amet. Ut vitae dui eget ligula porttitor iaculis et eu mauris. Integer molestie purus nisl, in volutpat lectus hendrerit in. Donec aliquam eros vitae diam rutrum consequat.\r\n\r\nCras suscipit lacus diam, vel vulputate orci suscipit eu. Vestibulum blandit nunc id fermentum ornare. Sed non enim quis sem fringilla efficitur. Donec nec metus leo. Duis nisi arcu, eleifend ac velit ac, vestibulum feugiat lorem. Pellentesque quis finibus risus. Duis facilisis, ante ut hendrerit dapibus, libero diam vulputate leo, sit amet fringilla ipsum sapien a sapien. Suspendisse potenti. Nullam at leo ut sem vulputate tincidunt.\r\n\r\nMauris aliquam est a magna dapibus viverra. Vivamus faucibus pulvinar ullamcorper. Cras scelerisque vehicula leo sed efficitur. Fusce scelerisque arcu at nunc malesuada interdum. Nulla interdum diam tempor quam venenatis venenatis. Sed egestas blandit sem, at porta felis. Mauris auctor ut elit eu egestas.\r\n\r\nNunc ligula leo, egestas non rutrum ut, accumsan nec metus. Nunc turpis eros, elementum vitae feugiat in, aliquet at turpis. Nam in nunc lorem. Maecenas sed tortor nibh. Praesent blandit rhoncus quam eu tempus. Ut at metus aliquet, sodales ante ac, mollis mi. Duis porttitor ante eu metus tincidunt aliquam. Donec vitae erat ac massa venenatis convallis sit amet sit amet odio. Pellentesque sodales elit ut varius tincidunt. Fusce eu pulvinar justo. Cras ante erat, convallis quis eros a, finibus sodales enim. Suspendisse hendrerit nisl augue, eget suscipit sapien rhoncus a. Vestibulum pretium ultrices felis, vel hendrerit justo posuere ut.\r\n\r\nDuis congue et odio in fringilla. Pellentesque efficitur mi id urna aliquet accumsan. Morbi magna enim, tincidunt sit amet tincidunt at, placerat eu lectus. Suspendisse luctus eget dui vitae eleifend. Nulla sed orci diam. Maecenas tempor leo purus, at euismod felis rhoncus at. In eros mauris, scelerisque at libero eu, ultrices semper nunc. Morbi a dictum felis. Mauris iaculis urna augue, ac tincidunt eros dictum vel. Integer ut pulvinar purus, id interdum sem. Proin quis ultrices neque. Sed pretium leo eu mi mattis, sit amet aliquam quam tincidunt.\r\n\r\nNam ultrices lorem eu feugiat ultricies. Pellentesque vehicula libero in erat viverra imperdiet. Sed ultricies vel eros quis luctus. In a eros congue, laoreet nulla quis, auctor velit. Donec facilisis semper vestibulum. In consequat risus eu elit tempus, eget ultricies libero convallis. Nam congue urna id efficitur auctor. Pellentesque ut venenatis nisl. Nullam lorem eros, luctus vitae vestibulum et, vehicula vitae ante. Vestibulum lobortis sem ac lectus gravida, sit amet cursus mi pharetra. Fusce semper luctus nulla in pulvinar. Sed euismod ac nibh id vulputate. Nulla at mauris quam. Aenean sed egestas orci.', 'php-8', '1606306142.jpg', 'PHP 8', '2020-11-22 09:53:36', '2020-11-26 16:59:19', 33);

-- --------------------------------------------------------

--
-- Table structure for table `socialnetwork`
--

CREATE TABLE `socialnetwork` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `socialnetwork`
--

INSERT INTO `socialnetwork` (`id`, `name`, `icon_name`, `url`, `created_at`, `updated_at`) VALUES
(1, 'linkedin', 'fa-linkedin', 'https://www.linkedin.com/in/floch-olivier/', '2020-11-21 16:01:37', '2020-11-21 16:01:40'),
(5, 'github', 'fa-github', 'https://github.com/OlivierFL/blog', '2020-11-21 16:01:50', '2020-11-21 16:01:50');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int NOT NULL,
  `user_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `admin_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user_name`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`, `updated_at`, `admin_id`) VALUES
(33, 'olivier', 'Olivier', 'Floch', 'olivier.floch@example.com', '$2y$10$tW7iQ1ik0hZt385j.C32neXaIHynXOObQGX.yJBzStu4/0esIJyEq', 'admin', '2020-10-04 12:27:52', '2020-11-26 12:52:58', 5),
(34, 'user', 'User', 'User', 'user@example.com', '$2y$10$iOIIoT5zT.YPgiWGUqUFBuces7ZkvNMAROmiXGZ9IR5u.eN7DR04.', 'user', '2020-10-04 12:28:53', '2020-11-25 12:19:19', NULL),
(35, 'anonymous35', 'anonymous35', 'anonymous35', 'anonymous35@example.com', '$2y$10$kr19lm/A4j31TO2Lkn8aT.fXiIwtWAAcXbY6durgkMbD0uCnrgkze', 'ROLE_DISABLED', '2020-10-09 08:31:56', '2020-10-09 09:00:03', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `socialnetwork`
--
ALTER TABLE `socialnetwork`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `socialnetwork`
--
ALTER TABLE `socialnetwork`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `post_id` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
