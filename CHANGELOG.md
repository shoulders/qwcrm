# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

Please report any bugs or features that you find so that they can be fixed.
To get more details visit https://quantumwarp.com/

## [3.1.2] 2019-11-04
### Changed
- Upgraded all code to OOP Coding Paradigm
- Messages are now rendered from a messages store allowing for multiple messages to be displayed
- Minimum PHP version is now 7.2.0

### Fixed
- Many Bugs

## [3.1.1] 2019-07-25
### Added
- Language files can now be dropped in to the /language/ folder which are automatically detected and can then be used

### Changed
- upgraded session handler to Joomla 3.9.8

### Fixed
- PHP 7.1+ Compatabilty
- Many Bugs
- date inputs can no longer be broken, date picker must be used

## [3.1.0] 2019-06-09
### Added
- SEF Router
- New Tax Systems VAT Cash Accounting, VAT Flat Rate (Basic Turnover), VAT Flat Rate (Cash Based Turnover)
- Proper Client refunds
- Other Income component
- Payments component (All payments are now separate to their parent transaction)
- An upgrader to allow for faster releases
- An Overview page for Invoices
- PHP Information viewer
- Client Logins (Not activated yet because there are no pages for them)
- Enhanced logging
- Whoops Error Handler for easier diagnosis of issues.
- All records can be cancelled or deleted (and the audit trail is respected)
- Check on all records when performing various actions (i.e. Cancel, delete...)
- There is probably other stuff I have forgotten about

### Changed
- Gift Certificates has been renamed to Vouchers
- Can now search Clients, Workorders, Schedules, Invoices, Payments and Vouchers
- Payment methods can now be enabled/disabled for sending or receiving separately
- Some record actions have now been moved in to 'status' pages
- Financial report is 'Tax System' aware
- Most if not all actions now have permissions
- Backend code has had a massive clean up
- Database has had massive clean up and is now logical and easy to read
- There is probably other stuff I have forgotten about

### Fixed
- Everything I could find. Too much to list here

## [3.0.0] - 2017-11-17
### Initial Release
- QWcrm is a complete rewrite of MyITCRM
- Can migrate all data from MyITCRM
- All features now work
- 650+ issues fixed / features added