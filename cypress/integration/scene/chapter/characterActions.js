describe('Character sidemenu actions', () => {

    beforeEach(() => {
        cy.visitSceneForChapter();
        cy.registerAjaxContainerAlias();
        cy.get('.side-menu ul li').contains('Postacie').parents('li').first().as('characters');
    });

    it('adds a character from another scene and removes it', () => {
        cy.get('@characters').within(() => {
            cy.get('.js-load-sublist').click();
        }).then(() => {
            cy.get('@ajax-container').contains('Dodawanie istniejącej postaci').should('be.visible');
            cy.get('@ajax-container').get('td').contains('Postać 2').parent().get('.js-list-action').click();
            cy.contains('Dodano postać "Postać 2" do sceny "Scena 1".').should('be.visible');
        });

        cy.get('@characters').within(() => {
            cy.get('.js-list-toggle').click();
            cy.get('.sublist-label').contains('Postać 2').next().find(".js-delete.btn-warning").click();
        }).then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Usunięto postać "Postać 2" ze sceny "Scena 1".').should('be.visible');
            });
        });
    });
});
