describe('Book sidemenu actions', () => {

    beforeEach(() => {
        cy.visitStandaloneBook();
    });

    it('opens a list of chapters', () => {
        cy.get('.side-menu ul li').contains('Rozdziały').parents('li').first().as('chapters');
        cy.get('@chapters').within(() => {
            cy.get('.js-list-toggle').click();
        }).then(() => {
            cy.contains(/^Rozdział 1$/).should('be.visible');
        });
    });

    it('opens a list of chapters on mobile', () => {
        cy.viewport(719, 800);
        cy.get('.side-menu ul [title="Rozdziały"]').as('chapters');
        cy.get('@chapters').click().then(() => {
            cy.get('.h4').contains('Rozdziały').should('be.visible');
        });

        cy.get('@chapters').parent().find('.js-list-toggle[title="Lista"]').click().then(() => {
            cy.get('@chapters').parent().find('.js-list-container').should('be.visible').contains(/^Rozdział 1$/).should('be.visible');
        });
    });
});
