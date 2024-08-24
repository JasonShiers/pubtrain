# PubTrain - A web app for capturing publication and training records for scientists (PHP/MySQL)

## Description

This was a successor project to Conference Tracker (a tool to catalogue and track attendance of scientific conferences and courses, which is still in active use and has not been made public). There was a need to keep records of the publications co-authored by members of staff, as well as training they had undertaken. This data was aggregated and used at a company level to demonstrate the highly skilled nature of our scientists. Data collection was originally a manual process involving a large Excel spreadsheet with limited capabilities. PubTrain enabled this information to be crowdsourced from the individual scientists, who could maintain their own training and publication records. Scientists could nominate their co-authors and co-attendees when entering a record to avoid unnecessary duplication of effort. The data could be queried using SQL to generate various statistics and summaries.

This system fell out of use in favour of commercial offerings, such as Bob HR, and publication data started being collected via Google Scholar profiles, so PubTrain was deprecated in favour of these better maintained alternatives.

## Prerequisites

- Dev system run on a LAMP stack (Ubuntu, Apache, MySQL, PHP), Production system was Windows Server 2008, IIS, MySQL, PHP
- Front end libraries: Bootstrap, DataTables, Chosen Select, jQuery
- Authentication via LDAPS connection to Active Directory
