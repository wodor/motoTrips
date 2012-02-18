Feature: users page
    As logged in  user
    In order to know what is going on, see where i attend
    And manage my profile and password
    I need to have a page to see that info

    As logged in  user
    In order to see who is who
    I need to see his profile page


Background:
  Given the site has following users:
  | username | password | email               |
  | Kreator  | 123456   | wodor@wodor.net     |
  | Konsumer | 22@222   | wod.orw@gmail.com   |
  | MrDoNothing | 22@222   | wodo.rw@gmail.com   |
  Given the site has following trips:
  | creator | title | description | descritpion_private |
  | Kreator | wypad w góry | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | wypad na Doły | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | 1234567890 | Lorem ipsum dolor sit amet | The very private description |
  Given the "wypad w góry" trip has the following signups:
        | user     | status     |
        | Konsumer              | approved        |
  Given the "wypad na Doły" trip has the following signups:
        | user     | status     |
        | Konsumer              | rejected   |
        | Konsumer              | resigned   |
  Given the "1234567890" trip has the following signups:
        | user     | status     |
        | Konsumer              | new   |

Scenario: Logged in User can go to his profile page
    Given I am logged in as "Konsumer" with "22@222" password
    And I go to "/"
    When I follow "dashboard"
    Then I should see "Moje wypady"
    And I should see "wypad w góry" in the "#approveds" element
    And I should see "1234567890" in the "#candidates" element
    And I should not see "wypad na Doły"

Scenario: OwnerOfTheTrip can see his trip
    Given I am logged in as "Kreator" with "123456" password
    And I go to "/"
    When I follow "dashboard"
    Then I should see "wypad w góry" in the "#owned" element

Scenario: Logged in user is able to put description about him
    Given I am logged in as "Konsumer" with "22@222" password
    And I go to "/"
    When I follow "dashboard"
    And I follow "change_description"
    Then I fill in "wodornet_mototripbundle_userdesciption[description]" with "I ride on xj600s'97"
    And I press "save"
    Then I should be on "user/2"
    And I should see "I ride on xj600s'97"

Scenario: Logged in user can see the link to description change form on the user page if he is that user
    Given I am logged in as "Konsumer" with "22@222" password
    And the "Konsumer" user has "I'm still looking for a bike" description
    When I go to "user/2"
    And I follow "change_description"
    Then I should see "I'm still looking for a bike" in the "textarea" element

Scenario: Logged in user can see profile of another user with description
    Given the "Konsumer" user has "I'm still looking for a bike" description
    And I am logged in as "Kreator" with "123456" password
    And I go to "/"
    When I go to "user/2"
    Then I should see "still looking for a bike"
    But I should not see an "#change_description" element

Scenario: Not Logged in user can only see name on the profile of another user
    Given the "Konsumer" user has "I'm still looking for a bike" description
    And I go to "/"
    When I go to "user/2"
    Then I should not see "I'm still looking for a bike"
    But I should see "Konsumer"


@todo
Scenario: Bygone Trips should be at the end of the list, marked, or maybe on another list.









