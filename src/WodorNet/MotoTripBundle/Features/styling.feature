Feature: permissions
    In order to be sure that I'm safe
    I need to others be unable to change my own data

Background:
  Given the site has following users:
  | username | password | email               |
  | Kreator  | 123456   | wodor@wodor.net     |
  | Konsumer | 22@222   | wod.orw@gmail.com   |
  | Lamer    | 123456   | wo.dorw@gmail.com   |
  | Pizza    | 123456   | w.odorw@gmail.com   |
  | Joey     | 123456   | wodo.rw@gmail.com   |
  | Jennifer | 123456   | wodorw@gmail.com   |
  | Fatal case of long name | 123456   | wodo.r.w@gmail.com   |
  Given the site has following trips:
  | creator | title | description | descritpion_private |
  | Kreator | wypad w góry | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | wypad w doły | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | 1234567890 1234567890 123456890 | Lorem ipsum dolor sit amet | The very private description |


@styling
Scenario: Fixture for styling approved
    Given the "1234567890 1234567890 123456890" trip has the following signups:
        | user     | status     |
        | Konsumer              | new        |
        | Lamer                 | approved   |
        | Pizza                 | approved   |
        | Joey                  | new        |
        | Jennifer              | new        |
        | Fatal case of long name | new      |
