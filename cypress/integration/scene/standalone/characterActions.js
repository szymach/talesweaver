describe('Character sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Postacie').parents('li').first().as('characters');
    });

    it('creates, displays, edits and deletes a character', () => {
        cy.get('@characters').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowa postać').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Postać{enter}');
            cy.contains('Pomyślnie dodano nową postać o imieniu "Postać"').should('be.visible');
        });

        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains(/^Postać$/).next().find('.js-display').click();
        }).then(() => {
            cy.get('#modal-display h4').contains(/^Postać$/).should('be.visible');
            cy.get('#modal-display').contains('Zamknij').click();
            cy.get('@characters').find('.js-list-toggle').click();
            cy.wait(2000);
        });

        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains(/^Postać$/).next().find('.js-edit-form').click();
        }).then(() => {
            cy.contains('Edycja postaci');
            cy.get('input[name="edit[name]"]').type('{selectall}Postać edytowana{enter}');
            cy.contains('Zapisano zmiany w postaci.').should('be.visible');
        });

        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.pagination .next a').click();
            // increasing timeout does not work
            cy.wait(2000);
            cy.get('.sublist-label').contains('Postać edytowana').next().find('.js-delete').click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Postać "Postać edytowana" została usunięta.').should('be.visible');
            });
        });
    });
});
