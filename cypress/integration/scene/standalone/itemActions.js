describe('Location sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Przedmioty').parents('li').first().as('items');
    });

    it('creates, edits and deletes an item', () => {
        cy.get('@items').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowy przedmiot').should('be.visible');
            cy.get('@ajax-container').find('input[name="create[name]"]').type('Przedmiot');
            cy.get('@ajax-container').contains('Zapisz').click();
            cy.contains('Pomyślnie dodano nowy przedmiot o nazwie "Przedmiot"').should('be.visible');
        });

        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains(/^Przedmiot$/).next().find('.js-display').click();
        }).then(() => {
            cy.get('#modal-display h4').contains(/^Przedmiot$/).should('be.visible');
            cy.get('#modal-display').contains('Zamknij').click();
            cy.get('#modal-display').should('not.be.visible');
            cy.get('@items').find('.js-list-toggle').click();
        });

        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains(/^Przedmiot$/).next().find('.js-edit-form').click();
        }).then(() => {
            cy.contains('Edycja przedmiotu');
            cy.get('@ajax-container').find('input[name="edit[name]"]').type('{selectall}Przedmiot edytowany');
            cy.get('@ajax-container').contains('Zapisz').click();
            cy.contains('Zapisano zmiany w przedmiocie.').should('be.visible');
        });

        cy.get('@items').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains('Przedmiot edy').next().find('.js-delete').click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Przedmiot "Przedmiot edytowany" został usunięty.').should('be.visible');
            });
        });
    });
});
