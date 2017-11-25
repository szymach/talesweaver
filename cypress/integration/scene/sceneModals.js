describe('Scene modals', function() {

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
        cy.get('.side-menu-toggle').click();
    });

    it('verifies side menu toggle', function () {
        cy.get('.side-menu').should('have.class', 'expanded');
        cy.contains('Postacie');
        cy.contains('Przedmioty');
        cy.contains('Miejsca');
        cy.contains('Wydarzenia');
        cy.contains('Spotkanie');

        cy.get('.side-menu-toggle').click();
        expect('.side-menu').to.not.contain('Postacie');
        expect('.side-menu').to.not.contain('Przedmioty');
        expect('.side-menu').to.not.contain('Miejsca');
        expect('.side-menu').to.not.contain('Wydarzenia');
        expect('.side-menu').to.not.contain('Spotkanie');
        cy.get('.side-menu').should('not.have.class', 'expanded');
    });

    it('opens character edit', function () {
        cy.get('.characters .fa-plus').click();
        cy.contains('Nowa postać');
        cy.get('.locations .fa-plus').click();
        cy.contains('Nowe miejsce');
        cy.get('.items .fa-plus').click();
        cy.contains('Nowy przedmiot');
        cy.get('.events .btn-success').click();
        cy.contains('Nowe wydarzenie');
    });
});
