describe('Location sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Miejsca').parents('li').first().as('locations');
    });

    it('adds a location from another scene and removes it', () => {
        cy.get('@locations').within(() => {
            cy.get('.js-load-sublist').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Dodawanie istniejącego miejsca').should('be.visible');
            cy.get('@ajax-container').get('td').contains('Miejsce 2').parent().get('.js-list-action').click();
            cy.contains('Dodano miejsce "Miejsce 2" do sceny "Scena 1".').should('be.visible');
        });

        cy.get('@locations').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.sublist-label').contains('Miejsce 2').next().find(".js-delete.btn-warning").click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Usunięto miejsce "Miejsce 2" ze sceny "Scena 1".').should('be.visible');
            });
        });
    });
});
