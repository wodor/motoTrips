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
  | creator | title | description | descritpion_private |
  | Kreator | wypad w góry | Lorem ipsum dolor sit amet | The very private description |

@mink:symfony
Scenario: Add a trip
    Given I am logged in as "Kreator" with "123456" password
    When I follow "dodaj wypad"
    When I fill in the following:
        | wodornet_mototripbundle_triptype[title]   | The testing test of the test |
        | wodornet_mototripbundle_triptype[description]   | Hello There! Hello There! Hello There! Hello There! Hello There! Hello There!     |
        | wodornet_mototripbundle_triptype[descriptionPrivate]   | Hello There!  Hello There! Hello There! Hello There! Hello There! Hello There! Hello There! Hello There!    |
        | wodornet_mototripbundle_triptype[location][lat] | 41.2757331547    |
        | wodornet_mototripbundle_triptype[location][lng] | 22.6757812500    |
    #And I click randomly on the map in "map_canvas".
    And I press "Zapisz"
    Then I should see "The testing test of the test"
    And I should be on "/trip/2/show"
    And I should see "Edytuj wypad"

Scenario: Go to join trip
    Given I am logged in as "Konsumer" with "22@222" password
    And I go to "/trip/1/show"
    And I follow "Dołącz do wypadu"
    Then I should see "Dołącz do wypadu"

Scenario: Not logged in user can see public trip
    When I go to "/trip/1/show"
    Then I should see "Dołącz do wypadu"

Scenario: Delete trip with signups
    Given the site has following trips:
        | creator | title | description | descritpion_private |
        | Kreator | to delete | Lorem ipsum dolor sit amet | The very private description |
    And the "to delete" trip has the following signups:
         | user     | status |
         | Konsumer | approved |
    Given I am logged in as "Kreator" with "123456" password
    And I go to "/trip/2/show"
    And I follow "Usuń wypad"
    Then I should be on "/dashboard"

Scenario:Creator's name is visible
    When I go to "trip/1/show"
    Then I should see "Kreator"