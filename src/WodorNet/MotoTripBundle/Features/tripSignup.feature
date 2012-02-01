Feature: permissions
    In order to be sure that I'm safe
    I need to others be unable to change my own data

Background:
  Given the site has following users:
  | username | password | email               |
  | Kreator  | 123456   | wodor@wodor.net     |
  | Konsumer | 22@222   | wod.orw@gmail.com   |
  | Lamer    | 123456   | wo.dorw@gmail.com   |
  Given the site has following trips:
  | creator | title | description | descritpion_private |
  | Kreator | wypad w góry | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | wypad w doły | Lorem ipsum dolor sit amet | The very private description |


Scenario: Join trip when you're allowed
    Given I am logged in as "Konsumer" with "22@222" password
    Given I go to "/tripsignup/signup/1"
    And I fill in "wodornet_mototripbundle_tripsignuptype[description]" with "Hi I'm beria"
    When I do not follow redirects
    And I press "Wyślij"
    Then email with subject "Nowa osoba chce dołączyć do Twojego wypadu" should have been sent to "wodor@wodor.net"
    When I am redirected
    Then I should be on "trip/1/show"

Scenario: When user is not logged he's not allowed to join trip
    I should not be allowed to go to "/tripsignup/signup/1"

Scenario: When user is logged in he see private information, if he's not approved
    Given I am logged in as "Konsumer" with "22@222" password
    When I go to "trip/1/show"
    Then I should not see "The very private description"

Scenario: As a OwnerOfTheTrip I can see the list of candidates and I am able to approve them
    Given the "wypad w góry" trip has the following signups:
    | user     | status |
    | Konsumer | new    |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    Then I should see "Konsumer"
    When I do not follow redirects
    When I follow "Approve"
    Then email with subject "Kreator zgodził się na Twój udział w wypadzie 'wypad w góry'" should have been sent to "wod.orw@gmail.com"
    When I am redirected
    When I am logged in as "Konsumer" with "22@222" password
    And I go to "trip/1/show"
    Then I should see "The very private description"


Scenario: Owner And attendee cannot join the trip
    Given the "wypad w góry" trip has the following signups:
     | user     | status |
     | Konsumer | new    |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    Then I should not see "Dołącz do wypadu"
    #And I should not be allowed to go to "/tripsignup/signup/1". bug ?
    When I am logged in as "Konsumer" with "22@222" password
    And I go to "trip/1/show"
    Then I should not see "Dołącz do wypadu"
   # And I should not be allowed to go to "/tripsignup/signup/1". bug in context?
    When I am logged in as "Lamer" with "123456" password
    And I go to "trip/1/show"
    Then I should see "Dołącz do wypadu"


Scenario: Owner is able to reject candidate
    Given the "wypad w góry" trip has the following signups:
    | user     | status |
    | Konsumer | new    |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    Then I should see "Odrzuć"
    When I follow "Odrzuć"
    Then email with subject "Kreator nie zgodził się na Twój udział w wypadzie 'wypad w góry'" should have been sent to "wod.orw@gmail.com"





Scenario: As an User (which is not owner of the trip) I cannot see candidates
