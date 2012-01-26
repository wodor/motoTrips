Feature: tripCreation
    In order to find friends
    As a logged in user
    I need to be able to add trips

Background:
  Given the site has following users:
  | username | password | email               |
  | Kreator  | 123456   | wodor@wodor.net     |
  | Konsumer | 22@222   | wod.orw@gmail.com   |
  Given the site has following trips:
  | creator | title |
  | Kreator | wypad  w góry |


@javascript
Scenario: Add a trip
    Given I am logged in as "Kreator" with "123456" password
    When I follow "dodaj wypad"
    Then I should see "Dodaj Wypad"
    When I fill in the following:
        | wodornet_mototripbundle_triptype[title]   | The testing test of the test |
        | wodornet_mototripbundle_triptype[description]   | Hello There!     |
        | wodornet_mototripbundle_triptype[descriptionPrivate]   | Hello There!     |
    And I click randomly on the map in "map_canvas"
    And I press "Dodaj"
    Then I should see "The testing test of the test"


Scenario: Go to join trip
    Given I am logged in as "Konsumer" with "22@222" password
    And I go to "/trip/1/show"
    And I follow "Dołącz do wypadu"
    Then I should see "Dołącz do wypadu"


Scenario: Join trip when you're allowed
    Given I am logged in as "Konsumer" with "22@222" password
    Given I go to "/tripsignup/signup/1"
    And I fill in "wodornet_mototripbundle_tripsignuptype[description]" with "Hi Im beria"
    And I press "Wyślij"
    Then I should be on "trip/1/show"
    And User "Konsumer" should be in trip candiates for trip "1"
    And email with subject "Nowa osoba chce dołączyć do Twojego wypadu" should have been sent to "wodor@wodor.net"

