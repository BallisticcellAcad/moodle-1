# eTask topics format v3.2.3

**eTask topics format** is type of the course format. It is based on topics format and **includes grading table** on the top of the course page with aditional functionality such as a highlighting of final grade or seting value for grade to pass.

![eTask.png](https://bitbucket.org/repo/obeE8n/images/1531457827-eTask.png)

## Changelog

- `2017-05-14` `NEW` `IMPORTANT` eTask **configuration was refactored** and supports new Moodle standards, **reconfigure eTask**
- `2017-05-14` `NEW` module settings was extended with **ability to manage registered due date modules**, it means definition of module and its database field containings due date which is displayed in eTask activity tooltip 
- `2017-05-14` `NEW` **paging for eTask** is available, number of students per page can be defined in the eTask settings (default value is 10)
- `2017-05-14` `NEW` **support for groups mode** in a course, it means student can see only students from group and teacher can **filtering eTask by defined groups**
- `2017-05-07` `FIXED` problem with **showing teachers in eTask definitely fixed**
- `2017-01-26` new progress bars 'Passed' and 'Submitted' in an activity tooltip; changes in eTask topics format configuration, reconfigure your _private view_ settings (the name of config key was changed); show progress bars configuration added
- `2017-01-16` new design of the grade settings modal, tooltip and grade table; fix known issues
- `2017-01-15` end of support for Moodle 2.9
- `2016-12-30` change of the logic for numbering grade items in the table haed

## Installation

1. download files as a ZIP archive,
2. extract files to the folder named `etask`,
3. copy the `etask` folder to `course/format/` in your Moodle installation,
4. login to the administration and run the installation,
5. optionally update the settings of this module,
6. set course format to `eTask topics format`

## Settings

**Private view** of eTask is now available. You can edit this setting by visiting `Site administration` -> `Plugins` -> `Course formats` -> `eTask topics format`. **By default**, private view **is disabled**. If you enable it, students can see only their own grades, othervise they see grades of all students. **Be careful** and set this private view before using this course format in a course!

![eTask-privateView.png](https://bitbucket.org/repo/obeE8n/images/1791672877-eTask-privateView.png)

**Shows progress bars 'Passed' and 'Submited'** in the activity tooltip. Progress in each activity can motivates your students. They can see progress of submited assignments or passed activities as well.

**Number of students per page** allows change number of students on echa page of eTask table. Pagination is a new feature of eTask module.

**Registered due date modules** provide list of activity modules and specifies in which module's database field the due date value is stored. It helps you to menage due date using in activity tooltip.

![eTask-settings.png](https://bitbucket.org/repo/obeE8n/images/1316928046-eTask-settings.png)

## Features

- provides grading table of many activity types such as `assign`, `quiz`, `scorm`, `worksop` etc.
- show **tooltip** with `due date`, `grade to pass`, `passed` and `submitted progress bars`,
![eTask-tooltip.png](https://bitbucket.org/repo/obeE8n/images/435665784-eTask-tooltip.png)

- allow **settings for grade to pass** in a course editing mode and it includes scales as well,
![eTask-gradeSettingsModal.png](https://bitbucket.org/repo/obeE8n/images/2069089277-eTask-gradeSettingsModal.png)

- **highlights grade value** by different statuses (`submitted`, `passed`, `failed` or without highlighting if grade to pass is not defined). Only `assign` suports submitted status (if submission is required in an assign settings),
![eTask-gradeToPassMessage.png](https://bitbucket.org/repo/obeE8n/images/3484827625-eTask-gradeToPassMessage.png)

- all activities in an eTask grading table are **sorted from newest** because of the information value of actual activities,
- there are two views - with **editing mode** (teacher) or clasic **student view**:
- **teacher** can edit activities by clicking on activity headers. Grade to pass setting is available; links from activity headers goes to activity editation; links from grade area goes to grading; filtering by groups is available as well as pagination (students per page is configurable),
- **student** can click only on activity headers; link goes to activity detail; pagination is available; if student is part of defined group, only students from the group are in the eTask table.

## Scales

It is possible to use scales in the grading activities. It is necessary to **define scales ascending** (from the worst to the best value), e.g. `No, Yes`!