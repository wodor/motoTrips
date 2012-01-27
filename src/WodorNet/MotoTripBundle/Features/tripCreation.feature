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
    When I fill in the following:
        | wodornet_mototripbundle_triptype[title]   | The testing test of the test |
        | wodornet_mototripbundle_triptype[description]   | Hello There! Hello There! Hello There! Hello There! Hello There! Hello There!     |
        | wodornet_mototripbundle_triptype[descriptionPrivate]   | Hello There!  Hello There! Hello There! Hello There! Hello There! Hello There! Hello There! Hello There!    |
    And I click randomly on the map in "map_canvas"
    And I press "Zapisz"
    Then I should see "The testing test of the test"
    Then I should be on "/trip/2/show"

Scenario: Go to join trip
    Given I am logged in as "Konsumer" with "22@222" password
    And I go to "/trip/1/show"
    And I follow "Dołącz do wypadu"
    Then I should see "Dołącz do wypadu"


