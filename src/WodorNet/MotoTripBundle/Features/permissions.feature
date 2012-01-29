Feature: permissions
    In order to be sure that I'm safe
    I need to others be unable to change my own data

Background:
  Given the site has following users:
  | username | password | email               |
  | Kreator  | 123456   | wodor@wodor.net     |
  | Konsumer | 22@222   | wod.orw@gmail.com   |
  Given the site has following trips:
  | creator | title |
  | Kreator | wypad w góry |

Scenario: Join trip when you're allowed
    Given I am logged in as "Konsumer" with "22@222" password
    Given I go to "/tripsignup/signup/1"
    And I fill in "wodornet_mototripbundle_tripsignuptype[description]" with "Hi I'm beria"
    When I do not follow redirects
    And I press "Wyślij"
    And User "Konsumer" should be in trip candiates for trip "1"
    And email with subject "Nowa osoba chce dołączyć do Twojego wypadu" should have been sent to "wodor@wodor.net"
    When I am redirected
    Then I should be on "trip/1/show"

Scenario: When user is not logged he's not allowed to join trip
    I should not be allowed to go to "/tripsignup/signup/1"

@noweb
Scenario: As a OwnerOfTheTrip I can approve a Candidate to my trip, to let him became Attendee and see the details.
    Given the "wypad w góry" trip has the following signups:
    | user     | status |
    | Konsumer | new    |
    When signup of "Konsumer" for "wypad w góry" is approved
    And "Konsumer" has "VIEW" permission for "wypad w góry"
    And email with subject "Kreator zgodził się na Twój udział w wypadzie 'wypad w góry'" should have been sent to "wod.orw@gmail.com"


Scenario: As a OwnerOfTheTrip I can see the list of candidates
    Given the "wypad w góry" trip has the following signups:
    | user     | status |
    | Konsumer | new    |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    Then I should see "Konsumer"


Scenario: As an Attendee i can see private information of the trip

Scenario: As an Candidate I cannot see private information
Scenario: As an User (which is not owner of the trip) I cannot see candidates





@noweb
Scenario: Creator aquires OWNER permission and edits it
    Given I am "Kreator"
    And I create trip "Mr Kreator's Trip to hell and back"
    Then I have "OWNER" permission for "Mr Kreator's Trip to hell and back" trip
    And I edit trip "Mr Kreator's Trip to hell and back"
    Then I have "OWNER" permission for "Mr Kreator's Trip to hell and back" trip
