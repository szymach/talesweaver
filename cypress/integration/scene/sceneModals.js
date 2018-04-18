describe('Modal opening', function() {

    beforeEach(function () {
        cy.request({
            method: 'POST',
            url: '/login',
            form: true,
            body: {
                '_username': 'user@example.com',
                '_password': 'password'
            }
        }).then(function (response) {
            expect(response.status).to.eq(200);
        });

        cy.visit('/scene/list');
        cy.url().should('eq', Cypress.config('baseUrl') + '/scene/list');
        cy.get('.btn-primary[title="Edycja"]').click();
        cy.url().should('contain', Cypress.config('baseUrl') + '/scene/edit');
        cy.get('#ajax-container').as('ajax-container');
        cy.get('.side-menu ul li').contains('Postacie').parents('li').first().as('characters');
        cy.get('.side-menu ul li').contains('Przedmioty').parents('li').first().as('items');
        cy.get('.side-menu ul li').contains('Miejsca').parents('li').first().as('locations');
        cy.get('.side-menu ul li').contains('Wydarzenia').parents('li').first().as('events');
    });

    it('creates new character', function () {
        cy.get('@characters').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowa postać').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Postać{enter}');
            cy.contains('Pomyślnie dodano nową postać o imieniu "Postać"').should('be.visible');
        });
    });

    it('edits existing character', function () {
        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-edit-form').last().click();
        }).then(() => {
            cy.contains('Edycja postaci');
            cy.get('input[name="edit[name]"]').type(' edytowana{enter}');
            cy.contains('Zapisano zmiany w postaci.').should('be.visible');
        });
    });

    it('creates new item', function () {
        cy.get('@items').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowy przedmiot').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Przedmiot{enter}');
            cy.contains('Pomyślnie dodano nowy przedmiot o nazwie "Przedmiot"').should('be.visible');
        });
    });

    it('edits existing item', function () {
        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-edit-form').last().click();
        }).then(() => {
            cy.contains('Edycja przedmiotu');
            cy.get('input[name="edit[name]"]').type(' edytowany{enter}');
            cy.contains('Zapisano zmiany w przedmiocie.').should('be.visible');
        });
    });

    it('creates new location', function () {
        cy.get('@locations').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowe miejsce').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Miejsce{enter}');
            cy.contains('Pomyślnie dodano nowe miejsce o nazwie "Miejsce"').should('be.visible');
        });
    });

    it('edits existing location', function () {
        cy.get('@locations').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-edit-form').last().click();
        }).then(() => {
            cy.contains('Edycja miejsca');
            cy.get('input[name="edit[name]"]').type(' edytowane{enter}');
            cy.contains('Zapisano zmiany w miejscu.').should('be.visible');
        });
    });

    it('creates new event', function () {
        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Nowy"]').click();
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowe wydarzenie').should('be.visible');
            cy.get('@ajax-container').get('[name="create[name]"').type('Spotkanie');
            cy.get('@ajax-container').get('[name="create[model][root]"').select('Postać edytowana');
            cy.get('@ajax-container').get('[name="create[model][location]"').select('Miejsce edytowane');
            cy.get('@ajax-container').get('[name="create[model][relation]"').select('Postać do spotkania');
            cy.get('@ajax-container').get('form[name="create"] .btn-primary').click();
            cy.contains('Pomyślnie dodano nowe wydarzenie o nazwie "Spotkanie"').should('be.visible');
        });
    });

    it('edits existing event', function () {
        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Lista"]').click();
            cy.get('.js-load-form.js-edit-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Edycja wydarzenia').should('be.visible');
            cy.get('@ajax-container').get('[name="edit[name]"').type(' edytowane');
            cy.get('@ajax-container').get('[name="edit[model][root]"').select('Postać do spotkania');
            cy.get('@ajax-container').get('[name="edit[model][location]"').select('Miejsce edytowane');
            cy.get('@ajax-container').get('[name="edit[model][relation]"').select('Postać edytowana');
            cy.get('@ajax-container').contains('Zapisz').click();
            cy.contains('Zapisano zmiany w wydarzeniu.').should('be.visible');
        });
    });

    it('deletes a character', function () {
        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Postać "Postać edytowana" została usunięta.').should('be.visible');
            });
        });
    });

    it('deletes an item', function () {
        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Przedmiot "Przedmiot edytowany" został usunięty.').should('be.visible');
            });
        });
    });

    it('deletes a location', function () {
        cy.get('@locations').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Miejsce "Miejsce edytowane" zostało usunięte.').should('be.visible');
            });
        });
    });

    it('deletes an event', function () {
        cy.get('@events').within(() => {
            cy.get('.js-list-toggle[title="Lista"]').click();
            cy.get('.js-delete').last().click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Wydarzenie "Spotkanie edytowane" zostało usunięte.').should('be.visible');
            });
        });
    });
});
