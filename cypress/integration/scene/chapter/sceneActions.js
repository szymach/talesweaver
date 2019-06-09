describe('Add and delete a new scene through chapter form', () => {
    beforeEach(() => {
        cy.visit('/chapter/list');
        cy.url().should('eq', Cypress.config('baseUrl') + '/chapter/list');
        cy.get('tr').contains(/^Rozdział$/).parent().find('[title="Edycja"]').click().then(() => {
            cy.url().should('contain', Cypress.config('baseUrl') + '/chapter/edit');
        });

        cy.clickTab('Sceny');
    });

    it('Adds and delete a new scene via ajax submission', () => {
        cy.get('#scenes').find('[title="Nowa scena"]').click().then(() => {
            cy.get('#modal-form').contains('Nowa scena').should('be.visible');
            cy.get('#modal-form').find('input[name="next_scene[title]"]').type('Kolejna scena');
            cy.get('#modal-form').contains('Zapisz').click();
            cy.contains('Pomyślnie dodano nową scenę o tytule "Kolejna scena"').should('be.visible').parent().find('button').click();
        });

        cy.get('#scenes').contains('Kolejna scena').parent().find('[title="Usuń"]').click().then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Scena "Kolejna scena" została usunięta.').should('be.visible').parent().find('button').click();
            });
        });
    });
});
