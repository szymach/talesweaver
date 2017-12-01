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

    it('creates new character', function () {
        cy.get('.characters .fa-plus').click();
        cy.contains('Nowa postać');
        cy.get('input[name="create[name]"]').type('Postać{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('edits existing character', function () {
        cy.get('.characters button.js-edit-form').last().click();
        cy.contains('Edycja postaci');
        cy.get('input[name="edit[name]"]').type(' edytowana{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('creates new item', function () {
        cy.get('.items .fa-plus').click();
        cy.contains('Nowy przedmiot');
        cy.get('input[name="create[name]"]').type('Przedmiot{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('edits existing item', function () {
        cy.get('.items button.js-edit-form').click();
        cy.contains('Edycja przedmiotu');
        cy.get('input[name="edit[name]"]').type(' edytowany{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('creates new location', function () {
        cy.get('.locations .fa-plus').click();
        cy.contains('Nowe miejsce');
        cy.get('input[name="create[name]"]').type('Miejsce{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('edits existing location', function () {
        cy.get('.locations button.js-edit-form').click();
        cy.contains('Edycja miejsca');
        cy.get('input[name="edit[name]"]').type(' edytowane{enter}');
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('creates new event', function () {
        cy.get('.events .btn-success').click();
        cy.contains('Nowe wydarzenie');
        cy.get('[name="create[name]"').type('Spotkanie');
        cy.get('[name="create[model][root]"').select('Postać edytowana');
        cy.get('[name="create[model][location]"').select('Miejsce edytowane');
        cy.get('[name="create[model][relation]"').select('Postać do spotkania');
        cy.get('form[name="create"] .btn-primary').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('edits existing event', function () {
        cy.get('.events button[title="Edycja"]').click();
        cy.contains('Edycja wydarzenia');
        cy.get('[name="edit[name]"').type(' edytowane');
        cy.get('[name="edit[model][root]"').select('Postać do spotkania');
        cy.get('[name="edit[model][location]"').select('Miejsce edytowane');
        cy.get('[name="edit[model][relation]"').select('Postać edytowana');
        cy.get('#ajax-container form[name="edit"] .btn-primary').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('deletes characters', function () {
        cy.get('.characters .js-list-delete').first().click();
        cy.get('#modal-confirm').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
        cy.get('nav.navbar.navbar-default').scrollIntoView();
        cy.get('#success-alert .close').click();
        cy.get('.characters .js-list-delete').first().click();
        cy.get('#modal-confirm').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('deletes an item', function () {
        cy.get('.items .js-list-delete').first().click();
        cy.get('#modal-confirm').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('deletes a location', function () {
        cy.get('.locations .js-list-delete').first().click();
        cy.get('#modal-confirm').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });

    it('deletes an event', function () {
        cy.get('.events .js-list-delete').first().click();
        cy.get('#modal-confirm').click();
        cy.contains('Operacja wykonana pomyślnie!').should('be.visible');
    });
});
