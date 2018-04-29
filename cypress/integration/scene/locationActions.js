describe('Location sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneScene();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Miejsca').parents('li').first().as('locations');
    });

    it('creates, edits and deletes a location', () => {
        cy.get('@locations').within(() => {
            cy.get('.js-load-form').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Nowe miejsce').should('be.visible');
            cy.get('@ajax-container').get('input[name="create[name]"]').type('Miejsce{enter}');
            cy.contains('Pomyślnie dodano nowe miejsce o nazwie "Miejsce"').should('be.visible');
        });

        cy.get('@locations').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains(/^Miejsce$/).next().find('.js-edit-form').click();
        }).then(() => {
            cy.contains('Edycja miejsca');
            cy.get('input[name="edit[name]"]').type(' edytowane{enter}');
            cy.contains('Zapisano zmiany w miejscu.').should('be.visible');
        });

        cy.get('@locations').within(() => {
            cy.get('.js-list-toggle').click();
            cy.contains('Miejsce edy').next().find('.js-delete').click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Miejsce "Miejsce edytowane" zostało usunięte.').should('be.visible');
            });
        });
    });
});
