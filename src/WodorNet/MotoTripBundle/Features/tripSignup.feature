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
  Given the site has following trips:
  | creator | title | description | descritpion_private |
  | Kreator | wypad w góry | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | wypad w doły | Lorem ipsum dolor sit amet | The very private description |
  | Kreator | 1234567890 1234567890 123456890 | Lorem ipsum dolor sit amet | The very private description |


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
    When I follow "Akceptuj"
    Then email with subject "Kreator zgodził się na Twój udział w wypadzie 'wypad w góry'" should have been sent to "wod.orw@gmail.com"
    When I am redirected
    When I am logged in as "Konsumer" with "22@222" password
    And I go to "trip/1/show"
    Then I should see "The very private description"

Scenario: OwnerOfTheTrip can see private description
    Given I am logged in as "Kreator" with "123456" password
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


Scenario: OwnerOfTheTrip is able to reject candidate
    Given the "wypad w góry" trip has the following signups:
    | user     | status |
    | Konsumer | new    |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    Then I should see "Odrzuć"
    And I should see "Konsumer"
    When I do not follow redirects
    And I follow "Odrzuć"
    Then email with subject "Kreator nie zgodził się na Twój udział w wypadzie 'wypad w góry'" should have been sent to "wod.orw@gmail.com"
    Then I should not see "Konsumer"

Scenario: User cannot resign if he did not applied
     Given I am logged in as "Konsumer" with "22@222" password
     When I go to "trip/1/show"
     Then I should not see "Rezygnuj"

Scenario: Attendee is able to resign from the trip whe he's approved
    Given the "wypad w góry" trip has the following signups:
        | user     | status |
        | Konsumer | approved    |
    And I am logged in as "Konsumer" with "22@222" password
    When I go to "trip/1/show"
    Then I should see "Zrezygnuj z wypadu"
    When I do not follow redirects
    And I follow "Zrezygnuj z wypadu"
    Then email with subject "Konsumer zrezygnował z udziału w wypadzie 'wypad w góry'" should have been sent to "wodor@wodor.net"



Scenario: Attendee is able to resign from the trip whe he's not approved
    Given the "wypad w góry" trip has the following signups:
        | user     | status |
        | Konsumer | new    |
    And I am logged in as "Konsumer" with "22@222" password
    When I go to "trip/1/show"
    Then I should see "Zrezygnuj z wypadu"
    When I do not follow redirects
    And I follow "Zrezygnuj z wypadu"
    Then email with subject "Konsumer zrezygnował z udziału w wypadzie 'wypad w góry'" should have been sent to "wodor@wodor.net"


    When I go to "trip/1/show"
    Then I should see "wypad w góry"
    But I should not see "Zrezygnuj z wypadu"


Scenario: Attendee can rejoin after resign
     Given the "wypad w góry" trip has the following signups:
         | user     | status |
         | Konsumer | resigned    |
         | Konsumer | resigned    |
        When I am logged in as "Konsumer" with "22@222" password
        And I go to "trip/1/show"
        Then I should see "Dołącz do wypadu"
        When I follow "Dołącz do wypadu"
        And I fill in "wodornet_mototripbundle_tripsignuptype[description]" with "Hi I'm Konsumer"
        And I press "Wyślij"
        Then I should not see "Dołącz do wypadu"

Scenario: Attendee can resign after rejoin
    Given the "wypad w góry" trip has the following signups:
        | user     | status |
        | Konsumer | new        |
        | Konsumer | resigned   |
    When I am logged in as "Konsumer" with "22@222" password
    And I go to "trip/1/show"
    Then I should see "Zrezygnuj z wypadu"


Scenario: OwnerOfTheTrip is able to accept trip signup page
    Given the "wypad w góry" trip has the following signups:
         | user     | status | message |
         | Konsumer | new    | Mane tekel fares |
    And I am logged in as "Kreator" with "123456" password
    When I go to "trip/1/show"
    And I follow "Konsumer"
    Then I should see "Zaakceptuj"
    And I should see "Mane tekel fares"
    When I follow "Zaakceptuj"
    Then I should see "Konsumer" in the "table#approvedList" element

Scenario: Person other than OwnerOfTheTrip is not able to accept trip signup page and cannot see the message
    Given the "wypad w góry" trip has the following signups:
            | user     | status | message |
            | Konsumer | new    | Mane tekel fares |
    And I am logged in as "Konsumer" with "22@222" password
    Then I go to "tripsignup/1/show"
    Then I should not see "Zaakceptuj"
    And I should not see "Mane tekel fares"

