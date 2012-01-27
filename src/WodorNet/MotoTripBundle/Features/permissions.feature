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
  | Kreator | wypad  w góry |

Scenario: Join trip when you're allowed
    Given I am logged in as "Konsumer" with "22@222" password
    Given I go to "/tripsignup/signup/1"
    And I fill in "wodornet_mototripbundle_tripsignuptype[description]" with "Hi I'm beria"
    And I press "Wyślij"
    Then I should be on "trip/1/show"
    And User "Konsumer" should be in trip candiates for trip "1"
    And email with subject "Nowa osoba chce dołączyć do Twojego wypadu" should have been sent to "wodor@wodor.net"


Scenario: Creator aquires OwNER permission and edits it
    Given I am "Kreator"
    And I create trip "Mr Kreator's Trip to hell and back"
    Then I have "OWNER" permission for "Mr Kreator's Trip to hell and back" trip
    And I edit trip "Mr Kreator's Trip to hell and back"
    Then I have "OWNER" permission for "Mr Kreator's Trip to hell and back" trip

Scenario: Candidate obtains VIEW permission after beind approved by owner
    Given I am "Konsumer"
    And I Join trip "wypad w gory"
    And Owner approves "Konsumer" for trip "wypad w gory"
    Then I have "VIEW" permission for "wypad w gory"





