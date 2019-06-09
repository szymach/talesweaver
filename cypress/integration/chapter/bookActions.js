describe('Add and delete a new chapter through book form', () => {
    beforeEach(() => {
        cy.visit('/book/list');
        cy.url().should('eq', Cypress.config('baseUrl') + '/book/list');
        cy.get('tr').contains(/^Książka$/).parent().find('[title="Edycja"]').click().then(() => {
            cy.url().should('contain', Cypress.config('baseUrl') + '/book/edit');
        });

        cy.clickTab('Rozdziały');
    });

    it('Adds and delete a new chapter via ajax submission', () => {
        cy.get('#chapters').find('[title="Nowy rozdział"]').click().then(() => {
            cy.get('#modal-form').contains('Nowy rozdział').should('be.visible');
            cy.get('#modal-form').find('input[name="next_chapter[title]"]').type('Kolejny rozdział');
            cy.get('#modal-form').contains('Zapisz').click();
            cy.contains('Pomyślnie dodano nowy rozdział o tytule "Kolejny rozdział"').should('be.visible').parent().find('button').click();
        });

        cy.get('#chapters').contains('Kolejny rozdział').parent().find('[title="Usuń"]').click().then(() => {
            cy.get('#modal-confirm').click().then(() => {
                cy.contains('Rozdział "Kolejny rozdział" został usunięty.').should('be.visible').parent().find('button').click();
            });
        });
    });
});
