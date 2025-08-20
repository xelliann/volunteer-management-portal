
CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `vacancy` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `capacity` int(10) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `events` (`id`, `title`, `description`, `location`, `date`, `vacancy`, `capacity`, `created_at`) VALUES
(1, 'Beach Cleanup', 'Join us to clean the local beach area.', 'Mumbai Beach', '2025-08-20', 10, 50, '2025-08-12 04:48:08'),
(2, 'Tree Plantation Drive', 'Help us plant trees in the city park.', 'Pune City Park', '2025-08-22', 5, 30, '2025-08-12 04:48:08'),
(3, 'Food Distribution', 'Distribute food to those in need.', 'Delhi Shelter', '2025-08-25', 0, 40, '2025-08-12 04:48:08'),
(4, 'Blood Donation Camp', 'Donate blood and save lives.', 'Hyderabad Hospital', '2025-08-28', 23, 25, '2025-08-12 04:48:08'),
(5, 'School Painting', 'Volunteer to paint classrooms in a local school.', 'Chennai School', '2025-08-30', 2, 20, '2025-08-12 04:48:08'),
(6, 'Zero Vacancy Test', 'Event with no available spots.', 'Test Location A', '2025-09-01', 0, 100, '2025-08-12 05:13:51'),
(7, 'Full Capacity Vacancy', 'Vacancy equals capacity.', 'Test Location B', '2025-09-02', 50, 50, '2025-08-12 05:13:51'),
(8, 'Over Capacity Vacancy', 'Vacancy exceeds capacity (should be invalid).', 'Test Location C', '2025-09-03', 35, 30, '2025-08-12 05:13:51'),
(9, 'Negative Capacity Test', 'Vacancy is valid but capacity is zero.', 'Test Location D', '2025-09-04', 5, 0, '2025-08-12 05:13:51'),
(10, 'Max Int Test', 'Vacancy set to maximum unsigned INT value.', 'Test Location E', '2025-09-05', 10, 100000, '2025-08-12 05:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `volunteers`
--

CREATE TABLE `volunteers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteers`
--

INSERT INTO `volunteers` (`id`, `name`, `email`, `phone`, `password`, `is_admin`, `created_at`) VALUES
(1, 'mohit', 'mohisharma7890@gmail.com', '96823381213', '$2y$10$GlKgvzle0YpICnqjjspmPeKnGLKqtxakP4y.MCNGMb85zfkzKpEGO', 0, '2025-08-12 04:46:58');

-- --------------------------------------------------------

--
-- Table structure for table `volunteer_events`
--

CREATE TABLE `volunteer_events` (
  `id` int(11) NOT NULL,
  `volunteer_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `volunteer_events`
--

INSERT INTO `volunteer_events` (`id`, `volunteer_id`, `event_id`, `joined_at`) VALUES
(9, 1, 1, '2025-08-12 05:54:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `volunteers`
--
ALTER TABLE `volunteers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_join` (`volunteer_id`,`event_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `volunteers`
--
ALTER TABLE `volunteers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `volunteer_events`
--
ALTER TABLE `volunteer_events`
  ADD CONSTRAINT `volunteer_events_ibfk_1` FOREIGN KEY (`volunteer_id`) REFERENCES `volunteers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `volunteer_events_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
